# OpenStack Object Storage

Container yang sudah disetup:
- `public`
  Container object storage public yang dapat diakses siapapun tanpa menggunakan signature key
- `amdalnet`
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
Storage::disk('openstack')->putFile($reques->file('namafieldupload');

```
Ini adalah menyimpan dalam path, relatif terhadap container yang digunakan di setting

```
/public/test/123456.pdf
```

Akses ke url file 

```
http://s3.palapacloud.id:8080/9adeb3dc3c6a4eabad717eea434f0a9a/amdalnet/public/test/61dc1555d7782.docx

http://s3.palapacloud.id:8080/amdalnet/amdal.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=f35f44f34e984536a128c22e1b17d1fb%2F20220110%2FJakarta-1%2Fs3%2Faws4_request&X-Amz-Date=20220110T112138Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1800&X-Amz-Signature=23bc76b7a9f191936c69ae474f1dfea301374aef0f8813a407c28d258af03aef

echo Storage::disk('openstack')->temporaryUrl('public/test/61c92bbba477f.txt', Carbon::now()->addMinutes(30));
echo Storage::temporaryUrl('public/test/61c92bbba477f.txt', Carbon::now()->addMinutes(30));

```

Akses ke url file, ke container `public` bisa dilakukan dengan akses sebagai berikut

```
echo Storage::url('public/test/61c92bbba477f.txt');
```


---
# Swift API

```
swift list
swift list <container>

```

Application
Robotcall
