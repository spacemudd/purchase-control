<?php
/**
 * FIRSTSOLUTIONS / CLARIMOUNT CONFIDENTIAL
 * __________________
 *
 * 2017 FirstSolutions Association (https://firstsolu.com)
 * All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of FirstSolutions Association and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to FirstSolutions Associations
 * and its suppliers and may be covered by U.S. and Foreign Patents,
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from FirstSolutions Association.
 */

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $base_path_pdf = base_path('app/Binary/win/wkhtmltopdf.exe');

     // there's no image on win - fallback is composer. linux binary.
    $base_path_image = base_path('vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64');

} else {

    $base_path_pdf = base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
    $base_path_image = base_path('vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64');

}

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  => $base_path_pdf,
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => $base_path_image,
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),

);
