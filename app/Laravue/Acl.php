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
    const ROLE_EDITOR = 'editor';
    const ROLE_VISITOR = 'visitor';

    const ROLE_INITIATOR = 'initiator'; // pemrakarsa
    const ROLE_FORMULATOR = 'formulator'; // penyusun
    const ROLE_FORMULATOR_CHIEF = 'formulator-chief'; // penyusun ktpa
    const ROLE_FORMULATOR_MEMBER = 'formulator-member'; // penyusun atpa
    const ROLE_FORMULATOR_EXPERT = 'formulator-expert'; // penyusun tenaga ahli
    const ROLE_LPJP = 'lpjp'; // lembaga penyedia jasa penyusunan dokumen amdal (lpjp)
    const ROLE_PUSTANLING = 'pustanling'; // pustanling
    const ROLE_LUK = 'luk'; // lembaga uji kelayakan
    const ROLE_EXAMINER = 'examiner'; // tim uji kelayakan
    const ROLE_EXAMINER_CHIEF = 'examiner-chief'; // tim uji kelayakan
    const ROLE_EXAMINER_SECRETARY = 'examiner-secretary'; // tim uji kelayakan
    const ROLE_EXAMINER_ADMINISTRATION = 'examiner-administration'; // tim uji kelayakan
    const ROLE_EXAMINER_SUBSTANCE = 'examiner-substance'; // tim uji kelayakan
    const ROLE_EXAMINER_COMMUNITY = 'examiner-community'; // tim uji kelayakan
        
    const ROLE_ADMIN_SYSTEM = 'admin-system'; // admin system
    const ROLE_ADMIN_CENTRAL = 'admin-central'; // admin lh pusat
    const ROLE_ADMIN_REGIONAL = 'admin-regional'; // admin lh daerah
    const ROLE_PUBLIC = 'public';


    // const PERMISSION_VIEW_MENU_ELEMENT_UI = 'view menu element ui';
    // const PERMISSION_VIEW_MENU_COMPONENTS = 'view menu components';
    // const PERMISSION_VIEW_MENU_CHARTS = 'view menu charts';
    // const PERMISSION_VIEW_MENU_NESTED_ROUTES = 'view menu nested routes';
    // const PERMISSION_VIEW_MENU_TABLE = 'view menu table';
    // const PERMISSION_VIEW_MENU_ADMINISTRATOR = 'view menu administrator';
    // const PERMISSION_VIEW_MENU_THEME = 'view menu theme';
    // const PERMISSION_VIEW_MENU_CLIPBOARD = 'view menu clipboard';
    // const PERMISSION_VIEW_MENU_EXCEL = 'view menu excel';
    // const PERMISSION_VIEW_MENU_ZIP = 'view menu zip';
    // const PERMISSION_VIEW_MENU_PDF = 'view menu pdf';
    // const PERMISSION_VIEW_MENU_I18N = 'view menu i18n';

    // const PERMISSION_ARTICLE_MANAGE = 'manage article';
    
    const PERMISSION_VIEW_MENU_USER = 'view menu user';
    const PERMISSION_VIEW_MENU_PERMISSION = 'view menu permission';
    const PERMISSION_VIEW_MENU_ROLE = 'view menu role';

    const PERMISSION_VIEW_MENU_PROJECT = 'view menu project'; // kegiatan
    const PERMISSION_VIEW_MENU_PROJECT_PRE_SUBMISSION = 'view menu project pre submission'; // Belum Pengajuan PL
    const PERMISSION_VIEW_MENU_PROJECT_POST_SUBMISSION = 'view menu project post submission'; // Dalam Pengajuan PL
    const PERMISSION_VIEW_MENU_PROJECT_ON_PROCESS = 'view menu project on process'; // Dalam Pengujian
    const PERMISSION_VIEW_MENU_PROJECT_ISSUED = 'view menu project issued'; // Sudah Terbit PL
    const PERMISSION_VIEW_MENU_INITIATOR = 'view menu initiator'; // Pemrakarsa
    const PERMISSION_VIEW_MENU_LPJP = 'view menu lpjp'; // lpjp
    const PERMISSION_VIEW_MENU_FORMULATOR = 'view menu formulator'; // tenaga ahli penyusun
    const PERMISSION_VIEW_MENU_FORMULATOR_TEAM = 'view menu formulator team'; // tim penyusun
    const PERMISSION_VIEW_MENU_LUK = 'view menu luk'; // luk
    const PERMISSION_VIEW_MENU_EXPERT = 'view menu expert'; // expert bank

    const PERMISSION_VIEW_MENU_COMPONENT = 'view menu component'; // komponen kegiatan
    const PERMISSION_VIEW_MENU_PARAMS = 'view menu params & kbli'; // rona awal
    const PERMISSION_VIEW_MENU_SOP = 'view menu sop'; // sop
    const PERMISSION_VIEW_MENU_CLUSTER = 'view menu cluster'; // cluster
    // const PERMISSION_VIEW_MENU_TECHNICAL_REGULATIONS = 'view menu technical regulation';

    const PERMISSION_VIEW_MENU_PROFILE = 'view menu profile'; // profile
    
    const PERMISSION_VIEW_MENU_EXAMINER = 'view menu examiner';
    const PERMISSION_VIEW_MENU_EXAMINER_TEAM = 'view menu examiner team';
    
    // const PERMISSION_VIEW_MENU_ANNOUNCEMENT = 'view menu announcement';
    // const PERMISSION_VIEW_MENU_CONSULTATION = 'view menu public consultation';
    // const PERMISSION_VIEW_MENU_WORKSPACE = 'view menu digital workspace'; // digital workspace

    const PERMISSION_MANAGE_USER = 'manage user';
    const PERMISSION_MANAGE_PERMISSION = 'manage permission';
    const PERMISSION_MANAGE_ROLE = 'manage role';

    const PERMISSION_MANAGE_PROJECT = 'manage project';

    const PERMISSION_MANAGE_PARAMS = 'manage params';
    const PERMISSION_MANAGE_LPJP = 'manage lpjp';
    const PERMISSION_MANAGE_INITIATOR = 'manage initiator';
    const PERMISSION_MANAGE_FORMULATOR = 'manage formulator';
    const PERMISSION_MANAGE_EXAMINER = 'manage examiner';
    const PERMISSION_MANAGE_LUK = 'manage luk';
    const PERMISSION_MANAGE_EXPERT = 'manage expert';
    // const PERMISSION_MANAGE_ANNOUNCEMENT = 'manage announcement';
    const PERMISSION_MANAGE_CONSULTATION = 'manage consultation';
    const PERMISSION_MANAGE_SOP = 'manage sop';
    const PERMISSION_MANAGE_TECHNICAL_REGULATIONS = 'manage technical regulations';
    const PERMISSION_MANAGE_CLUSTER = 'manage cluster';
    
    // const PERMISSION_MANAGE_WORKSPACE = 'manage workspace';
    const PERMISSION_DO_ANNOUNCEMENT = 'do announcement';
    const PERMISSION_DO_ASSEMBLE_FORMULATOR = 'do assemble formulator';
    const PERMISSION_DO_PUBLISH_ANNOUNCEMENT = 'do publish announcement';
    const PERMISSION_DO_QUALIFICATION = 'do digital workspace - initial qualification form';
    const PERMISSION_DO_RKLRPL = 'do digital workspace - RKL/RPL';
    const PERMISSION_DO_ANDAL = 'do digital workspace - ANDAL';
    const PERMISSION_DO_UKLUPL = 'do digital workspace - UKL/UPL';
    const PERMISSION_DO_AMDAL = 'do digital workspace - Examiner AMDAL';
    const PERMISSION_DO_SK_PKPLH = 'do post check - Generate SK PKPLH';
    const PERMISSION_DO_SK_SKKL = 'do post check - Generate SK SKKL';
    const PERMISSION_DO_PUSH = 'do post check - Push To OSS';


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
