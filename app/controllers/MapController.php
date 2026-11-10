<?php
/**
 * Map Controller (Peta Persebaran Interaktif)
 * FaunaID - Proyek Edukasi Fauna Nusantara
 */

require_once APP_PATH . '/core/Controller.php';

class MapController extends Controller {
    public function index(): void {
        $regionModel = $this->model('RegionModel');
        $faunaModel = $this->model('FaunaModel');

        $data = [
            'title' => 'Peta Interaktif Persebaran Fauna Nusantara — FaunaID',
            'asiatisRegions'   => $regionModel->getByZone('Asiatis'),
            'peralihanRegions' => $regionModel->getByZone('Peralihan'),
            'australisRegions' => $regionModel->getByZone('Australis'),
            'asiatisFauna'     => $faunaModel->getByZone('Asiatis', 8),
            'peralihanFauna'   => $faunaModel->getByZone('Peralihan', 8),
            'australisFauna'   => $faunaModel->getByZone('Australis', 8),
            'zonesSummary'     => $regionModel->getZonesSummary()
        ];

        $this->view('map.index', $data);
    }
}

