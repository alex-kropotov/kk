<?php
declare(strict_types=1);

namespace App\Domain\Service;

use Back\Domain\Entity\Property;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ZipArchive;

class ExportService
{
    private string $exportPath;

    public function __construct()
    {
        $this->exportPath = 'storage/exports';

        if (!is_dir($this->exportPath)) {
            mkdir($this->exportPath, 0777, true);
        }
    }

    public function exportToExcel(Collection $properties, string $locale = 'sr'): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'ID',
            'Код',
            'Име',
            'Тип',
            'Статус',
            'Начин',
            'Цена',
            'Валута',
            'Град',
            'Општина',
            'Адреса',
            'Соби',
            'Спаваће собе',
            'Купатила',
            'Површина (m²)',
            'Површина плаца (m²)',
            'Спратови',
            'Тренутни спрат',
            'Година изградње',
            'Гаража',
            'Паркинг',
            'Лифт',
            'Тераса',
            'Подрум',
            'Поткровље',
            'Грејање',
            'Хлађење',
            'Намештено',
            'Обезбеђење',
            'Башта',
            'Поглед',
            'Активно',
            'Агент',
            'Датум креирања'
        ];

        $sheet->fromArray($headers, null, 'A1');

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0']
            ]
        ];
        $sheet->getStyle('A1:AH1')->applyFromArray($headerStyle);

        // Add data
        $row = 2;
        foreach ($properties as $property) {
            $detail = $property->getDetailByLocale($locale);

            $data = [
                $property->id,
                $property->code,
                $property->name,
                $this->translateType($property->type->value, $locale),
                $this->translateStatus($property->status->value, $locale),
                $this->translateDealType($property->deal_type->value, $locale),
                $property->price,
                $property->currency,
                $property->city,
                $property->municipality,
                $property->address,
                $property->rooms,
                $property->bedrooms,
                $property->bathrooms,
                $property->area,
                $property->lot_area,
                $property->floors,
                $property->current_floor,
                $property->year_built,
                $property->garage ? 'Да' : 'Не',
                $property->parking ? 'Да' : 'Не',
                $property->elevator ? 'Да' : 'Не',
                $property->terrace ? 'Да' : 'Не',
                $property->basement ? 'Да' : 'Не',
                $property->attic ? 'Да' : 'Не',
                $property->heating ? 'Да' : 'Не',
                $property->cooling ? 'Да' : 'Не',
                $property->furnished ? 'Да' : 'Не',
                $property->security ? 'Да' : 'Не',
                $property->garden ? 'Да' : 'Не',
                $property->view_type,
                $property->is_active ? 'Да' : 'Не',
                $property->user->username,
                $property->created_at->format('d.m.Y H:i')
            ];

            $sheet->fromArray($data, null, "A{$row}");
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'AH') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Save file
        $filename = 'properties_export_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = $this->exportPath . '/' . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $filePath;
    }

    public function exportToZip(Collection $properties, string $locale = 'sr'): string
    {
        // First create Excel file
        $excelPath = $this->exportToExcel($properties, $locale);

        // Create ZIP
        $zipFilename = 'properties_export_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = $this->exportPath . '/' . $zipFilename;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            throw new \RuntimeException('Cannot create ZIP file');
        }

        // Add Excel file
        $zip->addFile($excelPath, basename($excelPath));

        // Create properties folder in ZIP
        $zip->addEmptyDir('properties');

        // Add property files
        foreach ($properties as $property) {
            $propertyFolder = 'properties/' . $property->code;
            $zip->addEmptyDir($propertyFolder);

            // Add property info text file
            $info = $this->generatePropertyInfo($property, $locale);
            $zip->addFromString($propertyFolder . '/info.txt', $info);

            // Add images
            $images = $property->documents->where('type', 'image')->where('is_public', true);
            if ($images->count() > 0) {
                $zip->addEmptyDir($propertyFolder . '/images');
                foreach ($images as $index => $image) {
                    $imagePath = str_replace('uploads/', 'public/uploads/', $image->file_path);
                    if (file_exists($imagePath)) {
                        $extension = pathinfo($image->file_name, PATHINFO_EXTENSION);
                        $zip->addFile($imagePath, $propertyFolder . '/images/' . ($index + 1) . '.' . $extension);
                    }
                }
            }
        }

        $zip->close();

        // Delete temporary Excel file
        unlink($excelPath);

        return $zipPath;
    }

    private function generatePropertyInfo(Property $property, string $locale): string
    {
        $detail = $property->getDetailByLocale($locale);

        $info = "PROPERTY INFORMATION\n";
        $info .= "==================\n\n";
        $info .= "Code: {$property->code}\n";
        $info .= "Name: {$property->name}\n";
        $info .= "Type: {$this->translateType($property->type->value, $locale)}\n";
        $info .= "Status: {$this->translateStatus($property->status->value, $locale)}\n";
        $info .= "Deal Type: {$this->translateDealType($property->deal_type->value, $locale)}\n";
        $info .= "Price: {$property->price} {$property->currency}\n";

        if ($property->old_price) {
            $info .= "Old Price: {$property->old_price} {$property->currency}\n";
        }

        $info .= "\nLOCATION\n";
        $info .= "--------\n";
        $info .= "City: {$property->city}\n";
        if ($property->municipality) {
            $info .= "Municipality: {$property->municipality}\n";
        }
        if ($property->address) {
            $info .= "Address: {$property->address}\n";
        }

        $info .= "\nDETAILS\n";
        $info .= "-------\n";
        if ($property->rooms) $info .= "Rooms: {$property->rooms}\n";
        if ($property->bedrooms) $info .= "Bedrooms: {$property->bedrooms}\n";
        if ($property->bathrooms) $info .= "Bathrooms: {$property->bathrooms}\n";
        if ($property->area) $info .= "Area: {$property->area} m²\n";
        if ($property->lot_area) $info .= "Lot Area: {$property->lot_area} m²\n";
        if ($property->floors) $info .= "Floors: {$property->floors}\n";
        if ($property->current_floor) $info .= "Current Floor: {$property->current_floor}\n";
        if ($property->year_built) $info .= "Year Built: {$property->year_built}\n";

        $info .= "\nFEATURES\n";
        $info .= "--------\n";
        $features = [];
        if ($property->garage) $features[] = "Garage";
        if ($property->parking) $features[] = "Parking";
        if ($property->elevator) $features[] = "Elevator";
        if ($property->terrace) $features[] = "Terrace";
        if ($property->basement) $features[] = "Basement";
        if ($property->attic) $features[] = "Attic";
        if ($property->heating) $features[] = "Heating";
        if ($property->cooling) $features[] = "Cooling";
        if ($property->furnished) $features[] = "Furnished";
        if ($property->security) $features[] = "Security";
        if ($property->garden) $features[] = "Garden";
        $info .= implode(", ", $features) ?: "None";

        if ($detail) {
            $info .= "\n\nDESCRIPTION\n";
            $info .= "-----------\n";
            $info .= $detail->title . "\n\n";
            $info .= $detail->description;
        }

        $info .= "\n\nAGENT\n";
        $info .= "-----\n";
        $info .= "Username: {$property->user->username}\n";
        $info .= "Agent Code: {$property->user->agent_code}\n";

        return $info;
    }

    private function translateType(string $type, string $locale): string
    {
        $translations = [
            'sr' => ['house' => 'Kuća', 'apartment' => 'Stan', 'office' => 'Kancelarija'],
            'en' => ['house' => 'House', 'apartment' => 'Apartment', 'office' => 'Office'],
            'ru' => ['house' => 'Дом', 'apartment' => 'Квартира', 'office' => 'Офис']
        ];

        return $translations[$locale][$type] ?? $type;
    }

    private function translateStatus(string $status, string $locale): string
    {
        $translations = [
            'sr' => ['ready' => 'Useljivo', 'new' => 'Novogradnja', 'shared' => 'Deljeno'],
            'en' => ['ready' => 'Ready', 'new' => 'New Construction', 'shared' => 'Shared'],
            'ru' => ['ready' => 'Готово', 'new' => 'Новостройка', 'shared' => 'Совместное']
        ];

        return $translations[$locale][$status] ?? $status;
    }

    private function translateDealType(string $dealType, string $locale): string
    {
        $translations = [
            'sr' => ['sale' => 'Prodaja', 'rent' => 'Izdavanje'],
            'en' => ['sale' => 'For Sale', 'rent' => 'For Rent'],
            'ru' => ['sale' => 'Продажа', 'rent' => 'Аренда']
        ];

        return $translations[$locale][$dealType] ?? $dealType;
    }

    public function cleanupOldExports(): void
    {
        $files = glob($this->exportPath . '/*');
        $now = time();

        foreach ($files as $file) {
            if (is_file($file)) {
                // Delete files older than 24 hours
                if ($now - filemtime($file) >= 86400) {
                    unlink($file);
                }
            }
        }
    }
}
