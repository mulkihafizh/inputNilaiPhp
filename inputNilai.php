<?php
session_start();

if (!isset($_SESSION['siswa'])) {
    $_SESSION['siswa'] = [];
}

$rombel = ["PPLG XI-1", "PPLG XI-2", "PPLG XI-3", "PPLG XI-4", "PPLG XI-5"];

$alert = false;
$berhasil = false;
$nilaiAlrt = false;
$edit = false;

if(isset($_POST['send'])) {
    if (!empty($_POST['nama']) && !empty($_POST['nis']) && !empty($_POST['rombel']) && !empty($_POST['np']) && !empty($_POST['nk'])) {
        if ($_POST['np'] <= 100 && $_POST['nk'] <= 100) {
            $nilaiAkhir = round(($_POST['np'] + $_POST['nk']) / 2);
            if ($nilaiAkhir >= 85) {
                $ket = 'A';
            }elseif ($nilaiAkhir < 85 && $nilaiAkhir >=75) {
                $ket = 'B';
            }else {
                $ket = 'C';
            }

            $data = [
                "nama" => $_POST['nama'],
                "nis" => $_POST['nis'],
                "rombel" => $_POST['rombel'],
                "nilaiP" => $_POST['np'],
                "nilaiK" => $_POST['nk'],
                "nilaiAkhir" => $nilaiAkhir,
                "ket" => $ket,
            ];

            array_push($_SESSION['siswa'], $data);

            $berhasil = true;
        }else {
            $nilaiAlrt = true;
        }
    } else {
        $alert = true;
    }
}

if (isset($_GET['delete'])) {
    array_splice($_SESSION['siswa'], $_GET['index'], 1);
    
    header("Location: inputNilai.php");
}

if (isset($_GET['edit'])) {
    $index = $_GET['index'];
    $dataEdit = $_SESSION['siswa'][$index];
    
}

if (isset($_POST['update'])) {
    if (!empty($_POST['nama']) && !empty($_POST['rombel']) && !empty($_POST['nis']) && !empty($_POST['np']) && !empty($_POST['nk'])) {
        $nilai = round(($_POST['np'] + $_POST['nk']) / 2);
        if ($nilai >= 85) {
            $ket = "A";
        } elseif ($nilai < 85 && $nilai >= 75) {
            $ket = "B";
        } else {
            $ket = "C";
        }

        $index = $_POST['index'];
        $_SESSION['siswa'][$index]['nama'] = $_POST['nama'];
        $_SESSION['siswa'][$index]['nis'] = $_POST['nis'];
        $_SESSION['siswa'][$index]['rombel'] = $_POST['rombel'];
        $_SESSION['siswa'][$index]['nilaiP'] = $_POST['np'];
        $_SESSION['siswa'][$index]['nilaiK'] = $_POST['nk'];
        $_SESSION['siswa'][$index]['nilaiAkhir'] = $nilai;
        $_SESSION['siswa'][$index]['ket'] = $ket;
        
        $edit = true;
    }
}

if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: inputNilai.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Data Nilai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
    </script>
    <style type="text/css">
    </style>
</head>
<body>
    <?php
        if ($alert == true) {
    ?>
    <h4 class="bg-danger text-white p-2">Data tidak boleh kosong</h4>
    <?php
        }
    ?>
    <?php
        if ($berhasil == true) {
    ?>
    <h4 class="bg-success text-white p-2">Data berhasil disimpan</h4>
    <?php
        }
    ?>
    <?php
        if ($edit == true) {
    ?>
    <h4 class="bg-primary text-white p-2">Data berhasil diubah</h4>
    <?php
        }
    ?>
    <?php
        if ($nilaiAlrt == true) {
    ?>
    <h4 class="bg-danger text-white p-2">Nilai tidak boleh lebih dari 100</h4>
    <?php
        }
    ?>
    <form action="inputNilai.php" method="post">
    <?php
    if (isset($dataEdit)) {
    ?>
        <tr>
            <td colspan="2"><input type="number" name="index" value="<?= $_GET['index'] ?>" hidden></td>
        </tr>
    <?php
    }
    ?>
        <div class="inputForm m-5 w-75 mx-auto card  pt-0  text-light" style="background:#9e6b55; box-shadow:1px 1px 8px #9e6b55;">
            <h1 class="text-center card-header mt-0 px-5">Form Data Nilai Siswa</h1>
            <div class="p-5">
            <div class="mb-3 mt-1">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" placeholder="Nama Siswa" name="nama" value="<?= @$dataEdit['nama']?>" >
            </div>
            <div class="mb-3">
                <label for="nis" class="form-label">Nis</label>
                <input type="number" class="form-control" id="nis" placeholder="NIS" name="nis" value="<?= @$dataEdit['nis'] ?>">
            </div>
            <div class="mb-3">
                <label for="np" class="form-label">Nilai Pengetahuan</label>
                <input type="number" class="form-control" id="np" placeholder="Nilai Pengetahuan" name="np" value="<?= @$dataEdit['nilaiP'] ?>">
            </div>
            <div class="mb-3">
                <label for="nk" class="form-label">Nilai Keterampilan</label>
                <input type="number" class="form-control" id="nk" placeholder="Nilai Keterampilan" name="nk" value="<?= @$dataEdit['nilaiK'] ?>" >
            </div>
            <div class="mb-3">
                <label for="rombel" class="form-label">Rombel Siswa</label>
                <select id="rombel" class="form-select" name="rombel" >
                    <option value="" hidden>Pilih Rombel</option>

                    <?php
                            foreach ($rombel as $rm) {
                                if (isset($dataEdit)) {
                        ?>
                                <option value="<?= $rm ?>" <?= $rm == $dataEdit['rombel'] ? 'selected' : '' ?>><?= $rm ?></option>
                        <?php
                                } else {
                        ?>
                                <option value="<?= $rm ?>"><?= $rm ?></option>
                        <?php
                                }
                            }
                        ?>
                </select>
                
            </div>
            <?php
            if (isset($_GET['edit'])) {
            ?>
                <input type="submit" class="btn bg-primary text-white" name="update" value="Ubah"/>
            <?php
            }else {
            ?>
                <input type="submit" class="btn bg-success text-white" name="send" value="Simpan"/>
            <?php
            }
            ?>
            </div>
        </div>
    </form>
    <form method="POST" align="center" class="pb-5">
        <input class="btn bg-danger text-white" type="submit" name="reset" value="Reset Data" onclick="return confirm(`Reset Data Website?`)"/>
    </form>
    <?php
    if (!empty($_SESSION['siswa'])) {
    ?>
    <table class="mx-auto table w-75 table-bordered">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIS</th>
            <th>Rombel</th>
            <th>Pengetahuan</th>
            <th>Keterampilan</th>
            <th>Nilai Akhir</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    <?php 
        $no = 1;
        foreach($_SESSION['siswa'] as $key => $rslt) {
    ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $rslt['nama']; ?></td>
            <td><?= $rslt['nis']; ?></td>
            <td><?= $rslt['rombel']; ?></td>
            <td><?= $rslt['nilaiP'] ?></td>
            <td><?= $rslt['nilaiK'] ?></td>
            <td><?= $rslt['nilaiAkhir']; ?></td>
            <td><?= $rslt['ket']; ?></td>
            <td style="display: flex;">
                <a href="?edit&index=<?= $key ?>" class="mx-2 btn bg-primary text-white">Edit</a>
                <a href="?delete&index=<?= $key ?>" class="btn bg-danger text-white" onclick="return confirm(`Hapus data nilai peserta didik <?= $rslt['nama'] ?> ?`)">Hapus</a>
            </td>
        </tr>
    <?php
        }
    }
    ?>
    </table>
</body>
</html>