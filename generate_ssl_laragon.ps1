$project = "sistem_pengaduan_rs"
$laragon = "D:\laragon"

$crt = "$laragon\etc\ssl\$project.test.crt"
$key = "$laragon\etc\ssl\$project.test.key"

D:\laragon\bin\git\bin\openssl.exe req -x509 -nodes -days 365 `
-newkey rsa:2048 `
-keyout $key `
-out $crt `
-subj "/C=ID/ST=JawaTimur/L=Madiun/O=Laragon/CN=$project.test"

Write-Host ""
Write-Host "SSL berhasil dibuat"
