<?php

/**
 * Plugin Name: Forza Rent Booking
 * Description: Rent-a-car booking system (date range) with availability checks, admin list, and shortcode.
 * Version: 1.0.0
 * Author: Forza Rent
 */

if (!defined('ABSPATH')) exit;

function fr_get_price_tiers(int $car_id): array
{
    if (!function_exists('get_field')) {
        return [];
    }

    $price_list = get_field('price_list', $car_id);
    if (empty($price_list)) return [];

    return [
        '2_4'   => (float) ($price_list[0]['price'] ?? 0),
        '5_7'   => (float) ($price_list[1]['price'] ?? 0),
        '8_13'  => (float) ($price_list[2]['price'] ?? 0),
        '14'    => (float) ($price_list[3]['price'] ?? 0),
        '21'    => (float) ($price_list[4]['price'] ?? 0),
        '30'    => (float) ($price_list[5]['price'] ?? 0),
    ];
}


function fr_calculate_total_price(int $car_id, string $start, string $end): float
{
    $tiers = fr_get_price_tiers($car_id);
    if (empty($tiers)) {
        return 0;
    }

    $days = (strtotime($end) - strtotime($start)) / DAY_IN_SECONDS + 1;
    $days = max(1, (int) $days);

    // 30 / 31 – zakucano
    if ($days >= 30 && !empty($tiers['30'])) {
        return (float) $tiers['30'];
    }

    // 21–29
    if ($days >= 21 && $days <= 29 && !empty($tiers['21'])) {
        $per_day = (float) $tiers['21'] / 21;
        return round($per_day * $days, 2);
    }

    // 14–20
    if ($days >= 14 && $days <= 20 && !empty($tiers['14'])) {
        $per_day = (float) $tiers['14'] / 14;
        return round($per_day * $days, 2);
    }

    // 2–13
    if ($days >= 2 && $days <= 13 && !empty($tiers['2_4'])) {
        return (float) $tiers['2_4'] * $days;
    }

    return 0;
}




final class FR_Booking_Plugin
{
    const VERSION = '1.0.0';
    const TABLE   = 'fr_bookings';
    const CAP     = 'manage_options';

    private static $instance = null;

    public static function instance(): self
    {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct()
    {
        register_activation_hook(__FILE__, [$this, 'activate']);

        add_action('init', [$this, 'register_shortcodes']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);

        add_action('rest_api_init', [$this, 'register_rest_routes']);

        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_post_fr_booking_delete', [$this, 'handle_admin_delete']);
        add_action('admin_post_fr_booking_update_status', [$this, 'handle_admin_update_status']);
    }

    public static function table_name(): string
    {
        global $wpdb;
        return $wpdb->prefix . self::TABLE;
    }

    public function activate(): void
    {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table = self::table_name();
        $charset = $wpdb->get_charset_collate();

        // Note: start_date/end_date stored as DATE (YYYY-MM-DD)
        $sql = "CREATE TABLE {$table} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            car_id BIGINT(20) UNSIGNED NOT NULL,
            customer_name VARCHAR(190) NOT NULL,
            customer_email VARCHAR(190) NOT NULL,
            customer_phone VARCHAR(60) NULL,
            pickup_location VARCHAR(50) NULL,
            pickup_address VARCHAR(255) NULL,
            dropoff_location VARCHAR(50) NULL,
            dropoff_address VARCHAR(255) NULL,
            pickup_fee DECIMAL(10,2) NOT NULL DEFAULT 0,
            total_price DECIMAL(10,2) NOT NULL DEFAULT 0,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'confirmed',
            notes TEXT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY  (id),
            KEY car_id (car_id),
            KEY status (status),
            KEY start_date (start_date),
            KEY end_date (end_date)
        ) {$charset};";

        dbDelta($sql);

        if (get_option('fr_booking_admin_email') === false) {
            update_option('fr_booking_admin_email', get_option('admin_email'));
        }
    }

    public function register_shortcodes(): void
    {
        add_shortcode('fr_booking', [$this, 'shortcode_booking_form']);
    }

    public function enqueue_assets(): void
    {
        // Enqueue only if shortcode appears on page (cheap check)

        global $post;
        if (!($post instanceof WP_Post)) return;

        wp_register_script(
            'fr-booking',
            plugins_url('assets/fr-booking.js', __FILE__),
            [],
            self::VERSION,
            true
        );

        $car_id = get_queried_object_id();

        wp_localize_script('fr-booking', 'FR_BOOKING', [
            'restUrl' => esc_url_raw(rest_url('fr-booking/v1')),
            'nonce'   => wp_create_nonce('wp_rest'),
            'prices'  => fr_get_price_tiers($car_id),
        ]);


        wp_enqueue_style(
            'flatpickr',
            'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
            [],
            '4.6.13'
        );

        wp_enqueue_script(
            'flatpickr',
            'https://cdn.jsdelivr.net/npm/flatpickr',
            [],
            '4.6.13',
            true
        );

        wp_enqueue_style(
            'intl-tel-input',
            'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css',
            [],
            '18.2.1'
        );

        wp_enqueue_script(
            'intl-tel-input',
            'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js',
            [],
            '18.2.1',
            true
        );



        wp_enqueue_script('fr-booking');

        // Minimal styles (inline)
        $css = "
                .frb-row{display:flex;gap:12px;flex-wrap:wrap}
                .frb-field{display:flex;flex-direction:column;gap:6px;margin:10px 0;flex:1}
                .frb-field input,.frb-field textarea{padding:10px;border-radius:10px;border:1px solid rgba(0,0,0,.2)}
                .frb-actions{display:flex;gap:10px;margin-top:12px}
                .frb-btn{padding:10px 14px;border-radius:10px;border:1px solid rgba(0,0,0,.2);cursor:pointer;background:#fff}
                .frb-btn.primary{background:#111;color:#fff;border-color:#111}
                .frb-msg{margin-top:12px;padding:10px;border-radius:10px}
                .frb-msg.ok{background:rgba(0,128,0,.08);border:1px solid rgba(0,128,0,.2)}
                .frb-msg.err{background:rgba(255,0,0,.06);border:1px solid rgba(255,0,0,.18)}
                .frb-small{opacity:.75;font-size:12px;margin-top:6px}
            ";
        wp_register_style('fr-booking-inline', false);
        wp_enqueue_style('fr-booking-inline');
        wp_add_inline_style('fr-booking-inline', $css);
    }

    public function shortcode_booking_form($atts): string
    {
        $atts = shortcode_atts([
            'car_id' => 0,
        ], $atts);

        $car_id = absint($atts['car_id']);

        // If not passed, use current post ID (useful on single-car.php)
        if ($car_id === 0 && is_singular()) {
            $car_id = get_the_ID();
        }

        if ($car_id <= 0) {
            return '<div class="frb-msg err">Nedostaje car_id za booking formu.</div>';
        }

        $car_title = get_the_title($car_id);
        $car_title = $car_title ? esc_html($car_title) : 'Vozilo';

        ob_start();
?>
        <div class="frb-wrap" data-frb-car-id="<?php echo esc_attr($car_id); ?>">
            <h3 class="frb-title" style="margin:0 0 10px;"><?php echo $car_title; ?> — Rezervacija</h3>
            <div class="frb-row">
                <div class="frb-field">
                    <label for="customer_first_name">Ime</label>
                    <input type="text" name="customer_first_name" id="customer_first_name" autocomplete="given-name" placeholder="Ime" required>
                </div>

                <div class="frb-field">
                    <label for="customer_last_name">Prezime</label>
                    <input type="text" name="customer_last_name" id="customer_last_name" autocomplete="family-name" placeholder="Prezime" required>
                </div>
            </div>

            <div class="frb-row">
                <div class="frb-field">
                    <label for="start_date">Datum preuzimanja</label>
                    <input type="date" name="start_date" id="start_date" placeholder="Datum Preuzimanja" required>
                </div>
                <div class="frb-field">
                    <label for="end_date">Datum vraćanja</label>
                    <input type="date" name="end_date" id="end_date" placeholder="Datum vraćanja" required>
                </div>
            </div>
            <div>
                <div class="frb-field">
                    <label for="pickup_location">Lokacija preuzimanja</label>

                    <select name="pickup_location" id="pickup_location">
                        <option value="">Lokacija preuzimanja </option>
                        <option value="center">Beograd – Centar</option>
                        <option value="airport">Aerodrom Nikola Tesla</option>
                        <option value="custom">Adresa po zelji</option>
                    </select>



                    <input
                        type="text"
                        name="pickup_address"
                        id="pickup_address"
                        placeholder="Unesite tačnu adresu za pickup"
                        style="display:none;margin-top:8px;">
                </div>

                <div class="frb-field">
                    <label for="dropoff_location">Lokacija vraćanja</label>

                    <select name="dropoff_location" id="dropoff_location">
                        <option value="">Lokacija vraćanja</option>
                        <option value="center">Beograd – Centar</option>
                        <option value="airport">Aerodrom Nikola Tesla</option>
                        <option value="custom">Adresa po zelji</option>
                    </select>

                    <input
                        type="text"
                        name="dropoff_address"
                        id="dropoff_address"
                        placeholder="Unesite tačnu adresu za vraćanje"
                        style="display:none;margin-top:8px;">
                </div>

                <div class="frb-small">
                    Adresa po zelji se naplacuje dodatnih <b>20€</b>.
                </div>
            </div>



            <div class="frb-row">
                <div class="frb-field">
                    <label for="customer_email">Email</label>
                    <input type="email" name="customer_email" autocomplete="email" id="customer_email" placeholder="name@example.com" required>
                </div>
                <div class="frb-field">
                    <label for="frb-phon">Telefon</label>
                    <input
                        type="tel"
                        id="frb-phone"
                        name="customer_phone"
                        autocomplete="tel"
                        placeholder="60 1234567"

                        required>
                </div>
            </div>

            <div class="frb-total" style="margin-top:12px;font-weight:500;">
                Ukupna cena će biti obračunata nakon izbora datuma.
            </div>


            <div class="frb-small">
                Rezervacija se potvrđuje automatski nakon slanja forme.
            </div>

            <div class="frb-actions">
                <button class="btn-forza primary" data-frb-action="book">Iznajmi</button>
            </div>

            <div class="frb-msg" style="display:none"></div>
        </div>
    <?php
        return (string) ob_get_clean();
    }

    public function register_rest_routes(): void
    {
        register_rest_route('fr-booking/v1', '/book', [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'rest_create_booking'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('fr-booking/v1', '/calendar', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$this, 'rest_get_calendar'],
            'permission_callback' => '__return_true',
        ]);
    }

    private function parse_date(string $date): ?string
    {
        // Expect YYYY-MM-DD
        $date = trim($date);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return null;
        $ts = strtotime($date);
        if ($ts === false) return null;
        // normalize
        return gmdate('Y-m-d', $ts);
    }

    private function validate_range(string $start, string $end): array
    {
        $s = $this->parse_date($start);
        $e = $this->parse_date($end);
        if (!$s || !$e) return [false, 'Neispravan format datuma.'];

        if (strtotime($s) > strtotime($e)) return [false, 'Datum preuzimanja ne može biti posle datuma vraćanja.'];

        // Optional: prevent same-day end? (leave allowed)
        return [true, ''];
    }

    public function rest_get_calendar(WP_REST_Request $req): WP_REST_Response
    {
        global $wpdb;

        $car_id = absint($req->get_param('car_id'));
        if ($car_id <= 0) {
            return new WP_REST_Response([], 400);
        }

        $table = self::table_name();

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "
            SELECT start_date, end_date
            FROM {$table}
            WHERE car_id = %d
              AND status = 'confirmed'
            ORDER BY start_date ASC
            ",
                $car_id
            ),
            ARRAY_A
        );

        return new WP_REST_Response($rows, 200);
    }


    /**
     * Overlap logic:
     * Existing booking overlaps requested if:
     * existing.start_date <= requested.end_date AND existing.end_date >= requested.start_date
     * We consider pending+confirmed as blocking; cancelled does not block.
     */
    private function is_available(int $car_id, string $start, string $end, int $exclude_id = 0): bool
    {
        global $wpdb;

        $table = self::table_name();

        $sql = "
            SELECT COUNT(1)
            FROM {$table}
            WHERE car_id = %d
              AND status IN ('confirmed')
              AND start_date <= %s
              AND end_date >= %s
        ";

        $params = [$car_id, $end, $start];

        if ($exclude_id > 0) {
            $sql .= " AND id != %d";
            $params[] = $exclude_id;
        }

        $prepared = $wpdb->prepare($sql, $params);
        $count = (int) $wpdb->get_var($prepared);

        return $count === 0;
    }

    public function rest_create_booking(WP_REST_Request $req): WP_REST_Response
    {
        $nonce = $req->get_header('X-WP-Nonce');
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_REST_Response(['ok' => false, 'message' => 'Security check failed.'], 403);
        }

        $car_id = absint($req->get_param('car_id'));
        $start  = (string) $req->get_param('start_date');
        $end    = (string) $req->get_param('end_date');

        $first_name = sanitize_text_field((string) $req->get_param('customer_first_name'));
        $last_name  = sanitize_text_field((string) $req->get_param('customer_last_name'));
        $email  = sanitize_email((string) $req->get_param('customer_email'));
        $phone  = sanitize_text_field((string) $req->get_param('customer_phone'));
        $notes  = sanitize_textarea_field((string) $req->get_param('notes'));
        $full_name = trim($first_name . ' ' . $last_name);
        $pickup_location  = sanitize_text_field((string) $req->get_param('pickup_location'));
        $pickup_address   = sanitize_text_field((string) $req->get_param('pickup_address'));
        $dropoff_location = sanitize_text_field((string) $req->get_param('dropoff_location'));
        $dropoff_address  = sanitize_text_field((string) $req->get_param('dropoff_address'));

        $phone            = $phone ?: '';
        $notes            = $notes ?: '';

        $pickup_location  = $pickup_location ?: '';
        $pickup_address   = $pickup_address ?: '';
        $dropoff_location = $dropoff_location ?: '';
        $dropoff_address  = $dropoff_address ?: '';

        $pickup_fee = 0;

        if ($pickup_location === 'custom') {
            $pickup_fee = 20;
        }

        if ($pickup_location === 'custom' && $pickup_address === '') {
            return new WP_REST_Response([
                'ok' => false,
                'message' => 'Molimo unesite adresu za pickup.'
            ], 400);
        }

        if ($dropoff_location === 'custom' && $dropoff_address === '') {
            return new WP_REST_Response([
                'ok' => false,
                'message' => 'Molimo unesite adresu za vraćanje.'
            ], 400);
        }

        if ($car_id <= 0) return new WP_REST_Response(['ok' => false, 'message' => 'Neispravan car_id.'], 400);

        [$ok, $msg] = $this->validate_range($start, $end);
        if (!$ok) return new WP_REST_Response(['ok' => false, 'message' => $msg], 400);

        if (
            $full_name === '' ||
            $email === '' ||
            !is_email($email) ||
            $phone === ''
        ) {
            return new WP_REST_Response([
                'ok' => false,
                'message' => 'Ime, prezime, ispravan email i broj telefona su obavezni.'
            ], 400);
        }

        if (!preg_match('/^[0-9+\s\-()]{6,20}$/', $phone)) {
            return new WP_REST_Response([
                'ok' => false,
                'message' => 'Unesite ispravan broj telefona.'
            ], 400);
        }

        $start = $this->parse_date($start);
        $end   = $this->parse_date($end);


        if (!$start || !$end) {
            return new WP_REST_Response(['ok' => false, 'message' => 'Neispravan datum.'], 400);
        }

        $total_price = fr_calculate_total_price($car_id, $start, $end);
        $total_price += $pickup_fee;

        // Atomic-ish: check availability then insert.
        // For high concurrency, consider transaction/locking. For MVP, this is OK.
        if (!$this->is_available($car_id, $start, $end)) {
            return new WP_REST_Response(['ok' => false, 'message' => 'Vozilo je upravo postalo nedostupno za taj period.'], 409);
        }

        global $wpdb;
        $table = self::table_name();
        $now   = current_time('mysql');


        $inserted = $wpdb->insert($table, [
            'car_id'           => $car_id,
            'customer_name'    => $full_name,
            'customer_email'   => $email,
            'customer_phone'   => $phone,

            'pickup_location'  => $pickup_location,
            'pickup_address'   => $pickup_address,
            'dropoff_location' => $dropoff_location,
            'dropoff_address'  => $dropoff_address,
            'pickup_fee'       => $pickup_fee,
            'total_price'      => $total_price,

            'start_date'       => $start,
            'end_date'         => $end,
            'status'           => 'confirmed',
            'notes'            => $notes,
            'created_at'       => $now,
            'updated_at'       => $now,
        ], [
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%f',
            '%f',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        ]);

        if (!$inserted) {
            return new WP_REST_Response(['ok' => false, 'message' => 'Greška pri čuvanju rezervacije.'], 500);
        }

        $booking_id = (int) $wpdb->insert_id;
        $car_title = get_the_title($car_id);
        // Email klijentu - INSTANT potvrda
        $client_subject = 'Vaša rezervacija je potvrđena';

        $client_message =
            "Poštovani {$full_name},\n\n" .
            "Vaša rezervacija je USPEŠNO POTVRĐENA.\n\n" .
            "Vozilo: {$car_title}\n" .
            "Period: {$start} → {$end}\n" .
            "Ukupna cena: €" . number_format($total_price, 2) . "\n";

        if (!empty($pickup_location)) {
            $client_message .=
                "Pickup lokacija: {$pickup_location}\n" .
                "Pickup adresa: {$pickup_address}\n" .
                "Pickup doplata: €" . number_format($pickup_fee, 2) . "\n";
        }

        if (!empty($dropoff_location)) {
            $client_message .=
                "Dropoff lokacija: {$dropoff_location}\n" .
                "Dropoff adresa: {$dropoff_address}\n";
        }

        $client_message .=
            "\nHvala što ste izabrali " . get_bloginfo('name') . ".\n" .
            "Za sva pitanja slobodno nas kontaktirajte.\n\n" .
            get_bloginfo('name');

        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <no-reply@' . parse_url(home_url(), PHP_URL_HOST) . '>',
            'Reply-To: ' . get_option('admin_email'),
        ];

        wp_mail($email, $client_subject, $client_message, $headers);


        // Notify admin (basic)
        $admin_email = get_option('fr_booking_admin_email', get_option('admin_email'));
        $car_title   = get_the_title($car_id);
        $subject = 'Nova potvrđena rezervacija (#' . $booking_id . ')';
        $body = "Nova rezervacija.\n\n" .
            "Vozilo: {$car_title} (ID: {$car_id})\n" .
            "Period: {$start} → {$end}\n" .
            "Broj dana: " . ((strtotime($end) - strtotime($start)) / DAY_IN_SECONDS + 1) . "\n" .
            "Ukupna cena: €" . number_format($total_price, 2) . "\n\n" .
            "Pickup: {$pickup_location}\n" .
            "Pickup adresa: {$pickup_address}\n" .
            "Pickup doplata: €{$pickup_fee}\n\n" .
            "Dropoff: {$dropoff_location}\n" .
            "Dropoff adresa: {$dropoff_address}\n\n" .
            "Klijent: {$full_name}\n" .
            "Email: {$email}\n" .
            "Telefon: {$phone}\n" .
            "Napomena: {$notes}\n\n" .
            "Status: confirmed\n";

        @wp_mail($admin_email, $subject, $body);

        return new WP_REST_Response([
            'ok'        => true,
            'booking_id' => $booking_id,
            'message' => 'Rezervacija je uspešno evidentirana. Kontaktiraćemo vas uskoro putem email-a.',
        ], 201);
    }

    /** =========================
     *  Admin UI
     *  ========================= */
    public function admin_menu(): void
    {
        add_menu_page(
            'Rent a Car',
            'Rent a Car',
            self::CAP,
            'fr-booking',
            [$this, 'admin_page_bookings'],
            'dashicons-calendar-alt',
            56
        );

        add_submenu_page(
            'fr-booking',
            'Rezervacije',
            'Rezervacije',
            self::CAP,
            'fr-booking',
            [$this, 'admin_page_bookings']
        );

        add_submenu_page(
            'fr-booking',
            'Podešavanja',
            'Podešavanja',
            self::CAP,
            'fr-booking-settings',
            [$this, 'admin_page_settings']
        );
    }

    public function admin_page_settings(): void
    {
        if (!current_user_can(self::CAP)) return;

        if (isset($_POST['fr_save_settings']) && check_admin_referer('fr_save_settings')) {
            $email = sanitize_email((string) ($_POST['fr_booking_admin_email'] ?? ''));
            if ($email && is_email($email)) {
                update_option('fr_booking_admin_email', $email);
                echo '<div class="notice notice-success"><p>Sačuvano.</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>Unesi ispravan email.</p></div>';
            }
        }

        $admin_email = esc_attr(get_option('fr_booking_admin_email', get_option('admin_email')));
    ?>
        <div class="wrap">
            <h1>Podešavanja</h1>
            <form method="post">
                <?php wp_nonce_field('fr_save_settings'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="fr_booking_admin_email">Admin email za notifikacije</label></th>
                        <td><input type="email" class="regular-text" id="fr_booking_admin_email" name="fr_booking_admin_email" value="<?php echo $admin_email; ?>"></td>
                    </tr>
                </table>
                <p><button class="button button-primary" name="fr_save_settings" value="1">Sačuvaj</button></p>
            </form>
        </div>
    <?php
    }

    public function admin_page_bookings(): void
    {
        if (!current_user_can(self::CAP)) return;

        global $wpdb;
        $table = self::table_name();

        $status_filter = isset($_GET['status']) ? sanitize_text_field((string) $_GET['status']) : '';
        $allowed = ['confirmed', 'cancelled'];
        $where = '';
        $params = [];

        if ($status_filter && in_array($status_filter, $allowed, true)) {
            $where = "WHERE status = %s";
            $params[] = $status_filter;
        }

        $sql = "SELECT * FROM {$table} {$where} ORDER BY created_at DESC LIMIT 200";
        $rows = $params ? $wpdb->get_results($wpdb->prepare($sql, $params), ARRAY_A) : $wpdb->get_results($sql, ARRAY_A);

        $base_url = admin_url('admin.php?page=fr-booking');
    ?>
        <div class="wrap">
            <h1>Rezervacije</h1>

            <p>
                Filter:
                <a href="<?php echo esc_url($base_url); ?>">Sve</a> |
                <a href="<?php echo esc_url(add_query_arg('status', 'confirmed', $base_url)); ?>">Confirmed</a> |
                <a href="<?php echo esc_url(add_query_arg('status', 'cancelled', $base_url)); ?>">Cancelled</a>
            </p>

            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vozilo</th>
                        <th>Period</th>
                        <th>Cena</th>
                        <th>Klijent</th>
                        <th>Status</th>
                        <th>Kreirano</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$rows): ?>
                        <tr>
                            <td colspan="7">Nema rezervacija.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $r): ?>
                            <?php
                            $id = (int) $r['id'];
                            $car_id = (int) $r['car_id'];
                            $car_title = get_the_title($car_id);
                            $car_title = $car_title ? $car_title : ('#' . $car_id);
                            ?>
                            <tr>
                                <td><?php echo (int) $id; ?></td>
                                <td>
                                    <?php echo esc_html($car_title); ?>
                                    <div style="opacity:.75;font-size:12px;">Car ID: <?php echo (int) $car_id; ?></div>
                                </td>
                                <td><?php echo esc_html($r['start_date'] . ' → ' . $r['end_date']); ?></td>
                                <td>
                                    <b>€<?php echo number_format((float) $r['total_price'], 2); ?></b>
                                </td>
                                <td>
                                    <b><?php echo esc_html($r['customer_name']); ?></b><br>
                                    <?php echo esc_html($r['customer_email']); ?><br>
                                    <?php echo esc_html($r['customer_phone'] ?? ''); ?>

                                    <?php if (!empty($r['pickup_location'])): ?>
                                        <div style="font-size:12px;opacity:.8;margin-top:6px">
                                            <b>Pickup:</b> <?php echo esc_html($r['pickup_location']); ?><br>
                                            <?php echo esc_html($r['pickup_address']); ?><br>
                                            <b>Doplata:</b> €<?php echo esc_html($r['pickup_fee']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($r['dropoff_location'])): ?>
                                        <div style="font-size:12px;opacity:.8">
                                            <b>Dropoff:</b> <?php echo esc_html($r['dropoff_location']); ?><br>
                                            <?php echo esc_html($r['dropoff_address']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><b><?php echo esc_html($r['status']); ?></b></td>
                                <td><?php echo esc_html($r['created_at']); ?></td>
                                <td>
                                    <?php if ($r['status'] === 'confirmed'): ?>
                                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block;margin-right:6px;">
                                            <?php wp_nonce_field('fr_update_status_' . $id); ?>
                                            <input type="hidden" name="action" value="fr_booking_update_status">
                                            <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

                                            <input type="hidden" name="status" value="cancelled">
                                            <button class="button button-secondary" onclick="return confirm('Otkaži rezervaciju?');">
                                                Otkaži
                                            </button>

                                        </form>
                                    <?php endif; ?>

                                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block;">
                                        <?php wp_nonce_field('fr_delete_' . $id); ?>
                                        <input type="hidden" name="action" value="fr_booking_delete">
                                        <input type="hidden" name="id" value="<?php echo (int) $id; ?>">
                                        <button class="button button-link-delete" onclick="return confirm('Obrisati rezervaciju?');">Obriši</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <p style="opacity:.7;margin-top:12px;">Prikaz poslednjih 200 zapisa (MVP).</p>
        </div>
<?php
    }

    public function handle_admin_delete(): void
    {
        if (!current_user_can(self::CAP)) wp_die('Forbidden');

        $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
        if ($id <= 0) wp_die('Bad request');

        check_admin_referer('fr_delete_' . $id);

        global $wpdb;
        $table = self::table_name();
        $wpdb->delete($table, ['id' => $id], ['%d']);

        wp_safe_redirect(admin_url('admin.php?page=fr-booking'));
        exit;
    }

    public function handle_admin_update_status(): void
    {
        if (!current_user_can(self::CAP)) wp_die('Forbidden');

        $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
        $status = isset($_POST['status']) ? sanitize_text_field((string) $_POST['status']) : '';

        if ($id <= 0) wp_die('Bad request');

        check_admin_referer('fr_update_status_' . $id);

        $allowed = ['cancelled'];
        if (!in_array($status, $allowed, true)) wp_die('Bad status');

        global $wpdb;
        $table = self::table_name();
        $wpdb->update($table, [
            'status' => $status,
            'updated_at' => current_time('mysql'),
        ], ['id' => $id], ['%s', '%s'], ['%d']);



        // Pošalji email klijentu ako je otkazano
        if ($status === 'cancelled') {

            $booking = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM {$table} WHERE id = %d",
                    $id
                ),
                ARRAY_A
            );


            if ($booking && !empty($booking['customer_email'])) {
                $car_title = get_the_title((int) $booking['car_id']);
                $total_price = (float) ($booking['total_price'] ?? 0);


                if ($status === 'cancelled') {
                    $subject = 'Vaša rezervacija je otkazana';
                    $message =
                        "Poštovani {$booking['customer_name']},\n\n" .
                        "Nažalost, vaša rezervacija je OTKAZANA.\n\n" .
                        "Vozilo: {$car_title}\n" .
                        "Period: {$booking['start_date']} → {$booking['end_date']}\n\n" .
                        "Ukupna cena: €" . number_format($total_price, 2) . "\n\n" .
                        "Za više informacija, slobodno nas kontaktirajte.\n\n" .
                        get_bloginfo('name');

                    wp_mail($booking['customer_email'], $subject, $message);
                }
            }
        }
        wp_safe_redirect(admin_url('admin.php?page=fr-booking'));
        exit;
    }
}

FR_Booking_Plugin::instance();
