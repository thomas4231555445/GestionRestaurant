<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\InventaireRepository;
use App\Repository\RestaurantRepository;
use App\Repository\VinsRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ListInventaireController extends AbstractController
{

    #[Route('/inventaire/export', name: 'inventaire_export')]
    public function export(Security $security,VinsRepository $vinsRepository, InventaireRepository $inventaireRepository, RestaurantRepository $restaurantRepository): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('User not found');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        if (!$restaurant) {
            throw $this->createNotFoundException('Restaurant not found');
        }

        // Récupérer les vins associés à l'ID du restaurant
        $inventaires = $inventaireRepository->findBy(['id_restaurant' => $restaurant->getId()]);

        $vin = $vinsRepository->findByRestaurantId($restaurant->getId());

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Vins');

        // En-têtes du tableau
        $sheet->setCellValue('A1', 'Code Vin');
        $sheet->setCellValue('B1', 'Qts');
        $sheet->setCellValue('C1', 'Date enregistrement');
        $sheet->setCellValue('D1', 'Appellation');
        $sheet->setCellValue('E1', 'Nom Vin');
        $sheet->setCellValue('F1', 'Millesime');



        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        // Remplir les données
        $row = 2;
        foreach ($inventaires as $inventaire) {
            $sheet->setCellValue('A' . $row, $inventaire->getCodeVin());
            $sheet->setCellValue('B' . $row, $inventaire->getQts());
            $sheet->setCellValue('C' . $row, $inventaire->getDateEnregistrement());
            $vin = $vinsRepository->findByCodeVin($inventaire->getCodeVin());
            if ($vin) {
                $sheet->setCellValue('D' . $row, $vin->getAppellation());
                $sheet->setCellValue('E' . $row, $vin->getNomVin());
                $sheet->setCellValue('F' . $row, $vin->getMillesime());
            }
            $rowStyle = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray($rowStyle);

            $row++;


        }



        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'inventaire.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($tempFile);

        return $this->file($tempFile, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
