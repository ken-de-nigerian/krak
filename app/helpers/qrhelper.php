<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\helpers;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Exception;

class qrhelper
{
    /**
     * Generates a QR code image using the provided content.
     *
     * @param string $qrCodeContent The content to encode in the QR code.
     * @param string $userid The user ID used to create a unique filename for the QR code.
     * @return bool Indicates whether the QR code creation was successful or not.
     */
    public static function createQR(string $qrCodeContent, string $userid): bool
    {
        try {
            // Initialize the PNG writer
            $writer = new PngWriter();

            // Create QR code
            $qrCode = QrCode::create($qrCodeContent)
                ->setSize(600)
                ->setMargin(10)
                ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);

            // Generate the QR code
            $result = $writer->write($qrCode);

            // Save file to folder
            $qrCodeDirectory = sprintf('%s/../../%s/%s/qr_image/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

            // Create directory if it doesn't exist
            if (!is_dir($qrCodeDirectory)) {
                mkdir($qrCodeDirectory, 0755, true);
            }

            // Save the QR code image to file
            $result->saveToFile($qrCodeDirectory . $userid . '_qrcode.png');

            return true; // QR code creation successful
        } catch (Exception) {
            // Handle exception
            // You might want to log or handle this exception differently
            return false;
        }
    }
}
