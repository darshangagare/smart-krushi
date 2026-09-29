<?php
/*
Plugin Name: Smart Krushi Disease
Description: Shows crop disease information from the Smart Krushi database.
Version: 1.0
*/

function smart_krushi_disease_shortcode() {
    global $wpdb;

    $output = '';

    $crop = isset($_GET['sk_crop']) ? sanitize_text_field(wp_unslash($_GET['sk_crop'])) : '';
    $disease = isset($_GET['sk_disease']) ? sanitize_text_field(wp_unslash($_GET['sk_disease'])) : '';

    $output .= '<h2>Crop Disease Information</h2>';

    $output .= '<form method="get"
    action="">';
    
    $output .= '<input type="hidden"
    name="page_id" value="102">';
    
    

    $output .= '<label><strong>Select Crop:</strong></label><br>';
    $output .= '<select name="sk_crop" required>';
    $output .= '<option value="">Select Crop</option>';
    $output .= '<option value="Pomegranate">Pomegranate</option>';
    $output .= '<option value="Orange">Orange</option>';
    $output .= '<option value="Grapes">Grapes</option>';
    $output .= '<option value="Marigold">Marigold</option>';
    $output .= '</select><br><br>';

    $output .= '<label><strong>Select Disease:</strong></label><br>';
    $output .= '<select name="sk_disease" required>';
    $output .= '<option value="">Select Disease</option>';
    $output .= '<option value="Bacterial Blight">Bacterial Blight</option>';
    $output .= '<option value="Citrus Canker">Citrus Canker</option>';
    $output .= '<option value="Downy Mildew">Downy Mildew</option>';
    $output .= '<option value="Leaf Spot">Leaf Spot</option>';
    $output .= '</select><br><br>';

    $output .= '<button type="submit">Show Information</button>';
    $output .= '</form>';

    $output .= '<script>
document.addEventListener("DOMContentLoaded", function() {
    const cropSelect = document.querySelector("select[name=\"sk_crop\"]");
    const diseaseSelect = document.querySelector("select[name=\"sk_disease\"]");

    const diseases = {
        "Pomegranate": "Bacterial Blight",
        "Orange": "Citrus Canker",
        "Grapes": "Downy Mildew",
        "Marigold": "Leaf Spot"
    };

    cropSelect.addEventListener("change", function() {
        const crop = cropSelect.value;

        diseaseSelect.innerHTML = "<option value=\"\">Select Disease</option>";

        if (diseases[crop]) {
            const option = document.createElement("option");
            option.value = diseases[crop];
            option.textContent = diseases[crop];
            diseaseSelect.appendChild(option);
        }
    });
});
</script>';

    if ($crop && $disease) {
        $table = 'crop_diseases';

        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE crop_name = %s AND disease_name = %s",
                $crop,
                $disease
            )
        );

        if ($result) {
            $output .= '<div style="margin-top:20px;">';
            $output .= '<h3>Disease Information</h3>';
            $output .= '<p><strong>Crop:</strong> ' . esc_html($result->crop_name) . '</p>';
            $output .= '<p><strong>Disease:</strong> ' . esc_html($result->disease_name) . '</p>';
            $output .= '<p><strong>Symptoms:</strong> ' . esc_html($result->symptoms) . '</p>';
            $output .= '<p><strong>Spray:</strong> ' . esc_html($result->spray) . '</p>';
            $output .= '<p><strong>Dose:</strong> ' . esc_html($result->dose) . '</p>';
            $output .= '<p><strong>Precautions:</strong> ' . esc_html($result->precautions) . '</p>';
            $output .= '</div>';
        } else {
            $output .= '<p>No information found for this crop and disease.</p>';
        }
    }

    return $output;
}

add_shortcode('smart_krushi_disease', 'smart_krushi_disease_shortcode');