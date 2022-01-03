# OpenStack Object Storage

Container yang sudah disetup:
- public
  Container object storage public yang dapat diakses siapapun tanpa menggunakan signature key
- amdalnet
  Container object storage yang bersifat private, hanya dapat diakses dengan menggunakan key signature
  tempUrlKey=rahasia123456

## Setting

`config/filesystem.php`

```
  'openstack' => [
    'driver'    => 'swift',
    'authUrl'   => env('OS_AUTH_URL', ''),
    'region'    => env('OS_REGION_NAME', ''),
    'user'      => env('OS_USERNAME', ''),
    'domain'    => env('OS_USER_DOMAIN_NAME', 'default'),
    'password'  => env('OS_PASSWORD', ''),
    'container' => env('OS_CONTAINER_NAME', ''),
    'tempUrlKey'=> env('OS_TEMPURL_KEY', 'rahasia123456'),
  ],

```

`.env`

```
FILESYSTEM_DRIVER=openstack
OS_AUTH_URL=http://jakarta-1-os.palapacloud.id:5000/v3/
OS_REGION_NAME="Jakarta-1"
OS_USERNAME="hb_client_1_1"
OS_USER_DOMAIN_NAME="Default"
OS_PASSWORD=z00ko6Wa
OS_CONTAINER_NAME="amdalnet"
OS_TEMPURL_KEY=rahasia123456

```

## Penggunaan
Untuk menyimpan path file di storage ke database, gunakan path relative dari container yang digunakan

```
$name = 'test/123456.pdf';
$file->storePubliclyAs('public', $name);

```
Ini adalah menyimpan dalam path, relatif terhadap container yang digunakan di setting

```
/public/test/123456.pdf
```

Akses ke url file 

```
echo Storage::disk('openstack')->temporaryUrl('public/test/61c92bbba477f.txt', Carbon::now()->addMinutes(30));
echo Storage::temporaryUrl('public/test/61c92bbba477f.txt', Carbon::now()->addMinutes(30));

```
