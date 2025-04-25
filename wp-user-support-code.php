<?php
/**
 * Plugin Name: WP User Support Code
 * Description: كود لإضافة رقم دعم فني مميز لكل مستخدم في ووردبريس.
 * Version: 1.0
 * Author: wppit.com
 * License: GPL2
 */

// إنسخ الكود فقط فى ملف فانكشن لقالب ووردبريس النشط فى موقعك حاليا

// توليد كود عشوائي لجميع الأعضاء عند تنشيط المكون الإضافي
function generate_random_code_for_all_users() {
    $args = array(
        'fields' => 'ID' // جلب جميع الأعضاء بدون تحديد دور معين
    );

    $users = get_users($args);

    foreach ($users as $user_id) {
        // تحقق مما إذا كان العضو لديه كود دعم بالفعل
        if (!get_user_meta($user_id, 'wppit_support_code', true)) {
            // توليد كود عشوائي
            $random_code = strtoupper(uniqid('SUP-')); // مثال على الكود: SUP-5F2A34A2C1B1
            update_user_meta($user_id, 'wppit_support_code', $random_code); // تخزين الكود في قاعدة البيانات
        }
    }
}
add_action('init', 'generate_random_code_for_all_users');

// توليد كود عشوائي عند تسجيل عضو جديد (ينطبق على أي نوع من الأعضاء)
add_action('user_register', 'generate_random_code_for_new_user');

function generate_random_code_for_new_user($user_id) {
    // تحقق مما إذا كان العضو لديه كود دعم بالفعل
    if (!get_user_meta($user_id, 'wppit_support_code', true)) {
        // توليد كود عشوائي
        $random_code = strtoupper(uniqid('SUP-')); // مثال على الكود: SUP-5F2A34A2C1B1
        update_user_meta($user_id, 'wppit_support_code', $random_code); // تخزين الكود في قاعدة البيانات
    }
}

// عرض كود الدعم في صفحة حساب الزبون (للأعضاء المسجلين)
add_action('woocommerce_account_dashboard', 'display_random_code_in_account_dashboard');

function display_random_code_in_account_dashboard() {
    $user_id = get_current_user_id();
    $random_code = get_user_meta($user_id, 'wppit_support_code', true); // استخدام المفتاح الجديد

    if ($random_code) {
        echo '<h2>كود الدعم الفني الخاص بك:</h2>';
        echo '<p>' . esc_html($random_code) . '</p>';
       echo '<p>إستخدم هذا الكود عند التواصل مع الدعم الفنى أو الدردشة المباشرة للتحقق من ملكية حسابك</p>';
    }
}



// إضافة صفحة الأكواد في لوحة التحكم
add_action('admin_menu', 'support_code_admin_menu');

function support_code_admin_menu() {
    add_menu_page('Support Codes', 'Support Codes', 'manage_options', 'support-codes', 'display_support_codes');
}

function display_support_codes() {
    // جلب جميع الأعضاء بغض النظر عن دورهم
    $args = array(
        'fields' => array('ID', 'user_login', 'user_email', 'user_registered')
    );
    $users = get_users($args);
    
    echo '<h1>كود التحقق من ملكية الحساب المخصص للدعم الفنى</h1>';
    
    // جدول منسق ومصمم بألوان واضحة
    echo '<table style="width:100%; border-collapse: collapse;">';
    echo '<thead style="background-color:#282828; color:white;">';
    echo '<tr>';
    echo '<th style="padding: 8px; border: 1px solid #ddd;">إسم المستخدم</th>';
    echo '<th style="padding: 8px; border: 1px solid #ddd;">البريد الإلكتروني</th>';
    echo '<th style="padding: 8px; border: 1px solid #ddd;">Support Code</th>';
    echo '<th style="padding: 8px; border: 1px solid #ddd;">تاريخ التسجيل</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($users as $user) {
        $support_code = get_user_meta($user->ID, 'wppit_support_code', true);
        echo '<tr style="background-color:#f9f9f9;">';
        echo '<td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($user->user_login) . '</td>';
        echo '<td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($user->user_email) . '</td>';
        echo '<td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($support_code) . '</td>';
        echo '<td style="padding: 8px; border: 1px solid #ddd;">' . esc_html(date('F j, Y', strtotime($user->user_registered))) . '</td>';
        echo '</tr>';
    }

     echo '</tbody>';
    echo '</table>';
    echo '<p style="margin-top:20px;">تم تطوير هذه الميزة بواسطة <a href="https://www.wppit.com" target="_blank">Wppit.com</a></p>';
}




// شورت كود لعرض كود الدعم واسم المستخدم
function wppit_support_code_display_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>يرجى تسجيل الدخول لعرض كود الدعم الخاص بك.</p>';
    }

    $user_id = get_current_user_id();
    $user_info = get_userdata($user_id);
    $username = $user_info->display_name;
    $support_code = get_user_meta($user_id, 'wppit_support_code', true);

    if (!$support_code) {
        return '<p>لم يتم العثور على كود الدعم الخاص بك.</p>';
    }

    $output = '<div class="wppit-support-box">';
    $output .= '<p>👋 مرحباً <strong>' . esc_html($username) . '</strong> | كود الدعم الخاص بك هو: <strong>' . esc_html($support_code) . '</strong></p>';
    $output .= '<p style="font-size: 14px; color: #888;">ملاحظة: لا تشارك كود الدعم الفني مع الآخرين، لأنه مرتبط بملكية حسابك في</p>';
    $output .= '</div>';

    return $output;
}
add_shortcode('wppit_support_code', 'wppit_support_code_display_shortcode');
