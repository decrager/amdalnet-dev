<?php
/**
 * File Acl.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */
namespace App\Laravue;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class Acl
 *
 * @package App\Laravue
 */
final class Acl
{
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_USER = 'user';
    const ROLE_VISITOR = 'visitor';
    const ROLE_INITIATOR = 'initiator'; // pemrakarsa
    const ROLE_FORMULATOR = 'formulator'; // penyusun
    const ROLE_INSTITUTION = 'institution'; // lembaga penyedia jasa penyusunan dokumen amdal (lpjp)
    const ROLE_ADMIN_INSTITUTION = 'admin-institution'; // pustanling
    const ROLE_EXAMINER = 'examiner'; // tim uji kelayakan
    const ROLE_EXAMINER_INSTITUTION = 'examiner-institution'; // lembaga uji kelayakan
    const ROLE_ADMIN_SYSTEM = 'admin-system'; // admin system
    const ROLE_ADMIN_CENTRAL = 'admin-central'; // admin lh pusat
    const ROLE_ADMIN_REGIONAL = 'admin-regional'; // admin lh daerah
    const ROLE_PUBLIC = 'public';


    const PERMISSION_VIEW_MENU_ELEMENT_UI = 'view menu element ui';
    const PERMISSION_VIEW_MENU_COMPONENTS = 'view menu components';
    const PERMISSION_VIEW_MENU_CHARTS = 'view menu charts';
    const PERMISSION_VIEW_MENU_NESTED_ROUTES = 'view menu nested routes';
    const PERMISSION_VIEW_MENU_TABLE = 'view menu table';
    const PERMISSION_VIEW_MENU_ADMINISTRATOR = 'view menu administrator';
    const PERMISSION_VIEW_MENU_THEME = 'view menu theme';
    const PERMISSION_VIEW_MENU_CLIPBOARD = 'view menu clipboard';
    const PERMISSION_VIEW_MENU_EXCEL = 'view menu excel';
    const PERMISSION_VIEW_MENU_ZIP = 'view menu zip';
    const PERMISSION_VIEW_MENU_PDF = 'view menu pdf';
    const PERMISSION_VIEW_MENU_I18N = 'view menu i18n';

    const PERMISSION_USER_MANAGE = 'manage user';
    const PERMISSION_ROLE_MANAGE = 'manage role';
    const PERMISSION_PERMISSION_MANAGE = 'manage permission';
    const PERMISSION_ARTICLE_MANAGE = 'manage article';
    
    const PERMISSION_VIEW_MENU_USER = 'menu user';
    const PERMISSION_VIEW_MENU_PERMISSION = 'menu permission';
    const PERMISSION_VIEW_MENU_ROLE = 'menu role';
    const PERMISSION_VIEW_MENU_PARAMS = 'menu params & kbli';
    const PERMISSION_VIEW_MENU_INSTITUTION = 'menu institution';
    const PERMISSION_VIEW_MENU_INITIATOR = 'menu initiator';
    const PERMISSION_VIEW_MENU_FORMULATOR = 'menu formulator';
    const PERMISSION_VIEW_MENU_EXAMINER = 'menu examiner';
    const PERMISSION_VIEW_MENU_EXAMINER_INSTITUTION = 'menu examiner institution';
    const PERMISSION_VIEW_MENU_EXPERT = 'menu expert';
    const PERMISSION_VIEW_MENU_ANNOUNCEMENT = 'menu announcement';
    const PERMISSION_VIEW_MENU_CONSULTATION = 'menu public consultation';
    const PERMISSION_VIEW_MENU_TECHNICAL_REGULATIONS = 'menu technical regulation';
    const PERMISSION_VIEW_MENU_CLUSTER = 'menu cluster';
    const PERMISSION_VIEW_MENU_PROJECT = 'menu project'; // kegiatan


    /**
     * @param array $exclusives Exclude some permissions from the list
     * @return array
     */
    public static function permissions(array $exclusives = []): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $permissions = Arr::where($constants, function($value, $key) use ($exclusives) {
                return !in_array($value, $exclusives) && Str::startsWith($key, 'PERMISSION_');
            });

            return array_values($permissions);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    public static function menuPermissions(): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $permissions = Arr::where($constants, function($value, $key) {
                return Str::startsWith($key, 'PERMISSION_VIEW_MENU_');
            });

            return array_values($permissions);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    /**
     * @return array
     */
    public static function roles(): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $roles =  Arr::where($constants, function($value, $key) {
                return Str::startsWith($key, 'ROLE_');
            });

            return array_values($roles);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }
}
