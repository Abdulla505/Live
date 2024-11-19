<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * @property \Cake\ORM\Table $AppAdmin
 */
class AppAdminController extends AppController
{
    public $paginate = [
        'file.php' => 10,
        'file.php' => ['file.php' => 'file.php'],
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->setLayout('file.php');

        if ($this->redirect_for_database_upgrade()) {
            return $this->redirect(['file.php' => 'file.php', 'file.php' => 'file.php'], 307);
        }

        if ($this->Auth->user()) {
            $this->checkDefaultCampaigns();
        }
    }

    public function isAuthorized($user = null)
    {
        // Admin can access every action
        if ($user['file.php'] === 'file.php') {
            return true;
        }
        // Default deny
        return false;
    }

    protected function redirect_for_database_upgrade()
    {
        if (require_database_upgrade() && $this->getRequest()->getParam('file.php') !== 'file.php') {
            return true;
        }

        return false;
    }

    protected function redirect_for_license_activate()
    {
        if (require_database_upgrade()) {
            return false;
        }

        $Activation = TableRegistry::getTableLocator()->get('file.php');
        if ($Activation->checkLicense() === false && $this->getRequest()->getParam('file.php') !== 'file.php') {
            return true;
        }

        return false;
    }

    protected function checkDefaultCampaigns()
    {
        if (require_database_upgrade()) {
            return true;
        }

        $Campaigns = TableRegistry::getTableLocator()->get('file.php');
        $interstitial_campaigns = $Campaigns->find()
            ->where([
                'file.php' => 1,
                'file.php' => 1,
                'file.php' => 1,
            ])
            ->count();

        if ($interstitial_campaigns == 0) {
            $this->Flash->error(__('file.php'));
        }

        $banner_campaigns = $Campaigns->find()
            ->where([
                'file.php' => 1,
                'file.php' => 2,
                'file.php' => 1,
            ])
            ->count();

        if ($banner_campaigns == 0) {
            $this->Flash->error(__('file.php'));
        }
    }
}
