<?php

declare(strict_types=1);

namespace App\Controller;

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

class ListVinsController extends AbstractController
{

    #[Route('/vins/export', name: 'vins_export')]
    public function export(Security $security, VinsRepository $vinsRepository, RestaurantRepository $restaurantRepository): Response
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
        $vins = $vinsRepository->findBy(['id_restaurant' => $restaurant->getId()]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Vins');

        // En-têtes du tableau
        $sheet->setCellValue('A1', 'Code Vin');
        $sheet->setCellValue('B1', 'Couleur');
        $sheet->setCellValue('C1', 'Nom Producteur');
        $sheet->setCellValue('D1', 'Domaine');
        $sheet->setCellValue('E1', 'Appellation');
        $sheet->setCellValue('F1', 'Nom Vin');
        $sheet->setCellValue('G1', 'Millesime');
        $sheet->setCellValue('H1', 'Contenant');
        $sheet->setCellValue('I1', 'Stock');
        $sheet->setCellValue('J1', 'Prix Achat HT');

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
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        // Remplir les données
        $row = 2;
        foreach ($vins as $vin) {
            $sheet->setCellValue('A' . $row, $vin->getCodeVin());
            $sheet->setCellValue('B' . $row, $vin->getCouleur());
            $sheet->setCellValue('C' . $row, $vin->getNomProducteur());
            $sheet->setCellValue('D' . $row, $vin->getDomaine());
            $sheet->setCellValue('E' . $row, $vin->getAppellation());
            $sheet->setCellValue('F' . $row, $vin->getNomVin());
            $sheet->setCellValue('G' . $row, $vin->getMillesime());
            $sheet->setCellValue('H' . $row, $vin->getCl() . ' Cl');
            $sheet->setCellValue('I' . $row, $vin->getStock() ?: '-');
            $sheet->setCellValue('J' . $row, $vin->getPrixAchatHt().' € ' ?: '-');

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
            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray($rowStyle);

            $row++;
        }

        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'listevins.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($tempFile);

        return $this->file($tempFile, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
