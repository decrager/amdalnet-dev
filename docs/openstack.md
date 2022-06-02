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

example
```
http://localhost:8000/api/home

```

```
http://s3.palapacloud.id:8080/swift/v1/9adeb3dc3c6a4eabad717eea434f0a9a/amdalnet/amdal.png
http://s3.palapacloud.id:8080/amdalnet/public/test/61dc1555d7782.docx

http://s3.palapacloud.id:8080/amdalnet/amdal.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=f35f44f34e984536a128c22e1b17d1fb%2F20220110%2FJakarta-1%2Fs3%2Faws4_request&X-Amz-Date=20220110T112138Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1800&X-Amz-Signature=23bc76b7a9f191936c69ae474f1dfea301374aef0f8813a407c28d258af03aef

echo Storage::disk('openstack')->temporaryUrl('public/test/61c92bbba477f.txt', Carbon::now()->addMinutes(30));
echo Storage::temporaryUrl('public/test/61c92bbba477f.txt', Carbon::now()->addMinutes(30));

```

Akses ke url file, ke container `public` bisa dilakukan dengan akses sebagai berikut

```
echo Storage::url('public/test/61c92bbba477f.txt');
```

## Openstack Setup

```
#!/usr/bin/env bash
export OS_AUTH_URL=http://jakarta-1-os.palapacloud.id:5000/v3/
export OS_PROJECT_ID=9adeb3dc3c6a4eabad717eea434f0a9a
export OS_PROJECT_NAME="Account #16"
export OS_USER_DOMAIN_NAME="Default"
if [ -z "$OS_USER_DOMAIN_NAME" ]; then unset OS_USER_DOMAIN_NAME; fi
export OS_PROJECT_DOMAIN_ID="default"
if [ -z "$OS_PROJECT_DOMAIN_ID" ]; then unset OS_PROJECT_DOMAIN_ID; fi
# unset v2.0 items in case set
unset OS_TENANT_ID
unset OS_TENANT_NAME
export OS_USERNAME="hb_client_1_1"
# With Keystone you pass the keystone password.
#echo "Please enter your OpenStack Password for project $OS_PROJECT_NAME as user $OS_USERNAME: "
#read -sr OS_PASSWORD_INPUT
export OS_PASSWORD=z00ko6Wa
# If your configuration has multiple regions, we set that information here.
# OS_REGION_NAME is optional and only valid in certain environments.
export OS_REGION_NAME="Jakarta-1"
# Don't leave a blank variable, unset it if it was empty
if [ -z "$OS_REGION_NAME" ]; then unset OS_REGION_NAME; fi
export OS_INTERFACE=public
export OS_IDENTITY_API_VERSION=3

```
---
# Swift API

```
swift list
swift list <container>

```

create container

```
swift post <container>
```

add secret key 

```
swift post -m "Temp-URL-Key:<key>"
```

upload file

```
swift upload <CONTAINER> <OBJECT_FILENAME>
swift upload amdalnet public/no-avatar.png
```

get tempurl

```
swift tempurl GET 3600 /v3/9adeb3dc3c6a4eabad717eea434f0a9a/amdalnet/amdal.png rahasia123456
```

result
```
/v3/9adeb3dc3c6a4eabad717eea434f0a9a/amdalnet/amdal.png?temp_url_sig=64fafd1a70d65a3bb2159e44b026e1223767dfa0&temp_url_expires=1654129189
```

alamat file jadi:
http://jakarta-1-os.palapacloud.id:5000/v3/9adeb3dc3c6a4eabad717eea434f0a9a/amdalnet/amdal.png?temp_url_sig=64fafd1a70d65a3bb2159e44b026e1223767dfa0&temp_url_expires=1654129189
- 

## Using s3 protokol

list

```
mc ls <container>/<object_path>
```

cp upload / download

```
mc cp 
mc cp openstack/amdalnet/amdal.png ~/
```