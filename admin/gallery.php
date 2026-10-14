<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";
$success = ""; $error = "";
// Tambahkan tabel media otomatis tanpa menghapus data galeri lama.
$conn->query("CREATE TABLE IF NOT EXISTS gallery_media (
 id INT AUTO_INCREMENT PRIMARY KEY,
 gallery_id INT NOT NULL,
 media_type ENUM('image','video','youtube') NOT NULL,
 media_path VARCHAR(500) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX (gallery_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
function galleryYoutubeId($url) {
 $url=trim($url); if ($url==='') return '';
 if (preg_match('~(?:youtu\\.be/|youtube\\.com/(?:watch\\?(?:.*&)?v=|embed/|shorts/))([A-Za-z0-9_-]{11})~', $url, $m)) return $m[1];
 return '';
}
$edit_id=(int)($_GET['edit']??0);
$editGallery=['id'=>0,'title'=>'','image'=>''];
if($edit_id){$st=$conn->prepare('SELECT * FROM gallery WHERE id=?');$st->bind_param('i',$edit_id);$st->execute();$editGallery=$st->get_result()->fetch_assoc() ?: $editGallery;}
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=(int)($_POST['id']??0); $title=trim($_POST['title']??''); $youtube=trim($_POST['youtube_url']??'');
 $currentImage=$editGallery['image']??''; $newFiles=[]; $error='';
 if($title==='') $error='Nama kategori wajib diisi.';
 if($youtube!=='' && !galleryYoutubeId($youtube)) $error='URL YouTube tidak valid. Gunakan tautan video YouTube biasa.';
 if($error==='' && isset($_FILES['media']['name']) && is_array($_FILES['media']['name'])){
  $allowed=['jpg'=>'image','jpeg'=>'image','png'=>'image','webp'=>'image','mp4'=>'video','webm'=>'video','ogg'=>'video'];
  for($i=0;$i<count($_FILES['media']['name']);$i++){
   if($_FILES['media']['error'][$i]===UPLOAD_ERR_NO_FILE) continue;
   if($_FILES['media']['error'][$i]!==UPLOAD_ERR_OK){$error='Ada file yang gagal diupload.';break;}
   $ext=strtolower(pathinfo($_FILES['media']['name'][$i],PATHINFO_EXTENSION));
   if(!isset($allowed[$ext])){$error='Format yang diizinkan: JPG, PNG, WEBP, MP4, WEBM, OGG.';break;}
   $limit=$allowed[$ext]==='video'?100*1024*1024:8*1024*1024;
   if($_FILES['media']['size'][$i]>$limit){$error=$allowed[$ext]==='video'?'Ukuran video maksimal 100 MB.':'Ukuran foto maksimal 8 MB.';break;}
   $newFiles[]=['i'=>$i,'ext'=>$ext,'type'=>$allowed[$ext]];
  }
 }
 if($error===''){
  if($id){$st=$conn->prepare('UPDATE gallery SET title=? WHERE id=?');$st->bind_param('si',$title,$id);$st->execute();$galleryId=$id;}
  else{$st=$conn->prepare('INSERT INTO gallery (title,image) VALUES (?,?)');$blank='';$st->bind_param('ss',$title,$blank);$st->execute();$galleryId=$conn->insert_id;}
  $uploadDir=__DIR__.'/../uploads/gallery/'; if(!is_dir($uploadDir)) mkdir($uploadDir,0755,true);
  foreach($newFiles as $f){$i=$f['i'];$filename='gallery_'.$galleryId.'_'.time().'_'.mt_rand(1000,9999).'.'.$f['ext'];
   if(!move_uploaded_file($_FILES['media']['tmp_name'][$i],$uploadDir.$filename)){$error='Sebagian file gagal dipindahkan ke folder uploads/gallery.';break;}
   $type=$f['type'];$st=$conn->prepare('INSERT INTO gallery_media (gallery_id,media_type,media_path) VALUES (?,?,?)');$st->bind_param('iss',$galleryId,$type,$filename);$st->execute();
   if($type==='image' && $currentImage===''){$st=$conn->prepare('UPDATE gallery SET image=? WHERE id=?');$st->bind_param('si',$filename,$galleryId);$st->execute();$currentImage=$filename;}
  }
  if($error===''){
   if($youtube!==''){$yt=galleryYoutubeId($youtube);$embed='https://www.youtube-nocookie.com/embed/'.$yt;$st=$conn->prepare("INSERT INTO gallery_media (gallery_id,media_type,media_path) VALUES (?,'youtube',?)");$st->bind_param('is',$galleryId,$embed);$st->execute();}
   $success='Kategori galeri berhasil disimpan. Media baru ditambahkan ke kategori ini.';
   $editGallery=['id'=>0,'title'=>'','image'=>''];
  }
 }
}
if(isset($_GET['delete_media'])){$mid=(int)$_GET['delete_media'];$st=$conn->prepare('SELECT media_path,media_type,gallery_id FROM gallery_media WHERE id=?');$st->bind_param('i',$mid);$st->execute();$m=$st->get_result()->fetch_assoc();if($m){if(in_array($m['media_type'],['image','video'],true)){ $f=__DIR__.'/../uploads/gallery/'.basename($m['media_path']);if(is_file($f))unlink($f); }$st=$conn->prepare('DELETE FROM gallery_media WHERE id=?');$st->bind_param('i',$mid);$st->execute();}header('Location: gallery.php?edit='.(int)($m['gallery_id']??0));exit;}
if(isset($_GET['delete'])){$id=(int)$_GET['delete'];$st=$conn->prepare('SELECT image FROM gallery WHERE id=?');$st->bind_param('i',$id);$st->execute();$r=$st->get_result()->fetch_assoc();$st=$conn->prepare('SELECT media_path,media_type FROM gallery_media WHERE gallery_id=?');$st->bind_param('i',$id);$st->execute();$rs=$st->get_result();while($m=$rs->fetch_assoc()){if(in_array($m['media_type'],['image','video'],true)){$f=__DIR__.'/../uploads/gallery/'.basename($m['media_path']);if(is_file($f))unlink($f);}}$st=$conn->prepare('DELETE FROM gallery_media WHERE gallery_id=?');$st->bind_param('i',$id);$st->execute();$st=$conn->prepare('DELETE FROM gallery WHERE id=?');$st->bind_param('i',$id);$st->execute();header('Location: gallery.php');exit;}
$gallery=$conn->query('SELECT * FROM gallery ORDER BY id DESC');
?>
<!DOCTYPE html><html lang="id"><head><?php include __DIR__."/../includes/head.php"; ?><link rel="stylesheet" href="assets/admin.css"><title>Manage Gallery</title></head><body>
<?php include __DIR__."/includes/header.php"; ?><div class="container py-5"><div class="d-flex justify-content-between mb-4"><div><h1>Gallery</h1><p class="text-muted">Kelola kategori berisi banyak foto, video lokal, dan video YouTube.</p></div><a href="dashboard.php" class="btn btn-secondary">Dashboard</a></div>
<?php if($success):?><div class="alert alert-success"><?=htmlspecialchars($success)?></div><?php endif;?><?php if($error):?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif;?>
<div class="card border-0 shadow-sm mb-4"><div class="card-body p-4"><h3><?= $editGallery['id']?'Edit':'Tambah' ?> Kategori Gallery</h3><form method="post" enctype="multipart/form-data"><input type="hidden" name="id" value="<?=(int)$editGallery['id']?>"><div class="mb-3"><label class="form-label">Nama kategori / judul</label><input name="title" class="form-control" required value="<?=htmlspecialchars($editGallery['title'])?>" placeholder="Contoh: Aktivitas Produksi"></div>
<div class="mb-3"><label class="form-label">Pilih banyak foto atau video</label><input type="file" name="media[]" class="form-control" multiple accept=".jpg,.jpeg,.png,.webp,.mp4,.webm,.ogg"><div class="form-text">Foto maksimal 8 MB per file. Video MP4/WEBM/OGG maksimal 100 MB per file. Saat edit, media yang sudah ada tidak dihapus.</div></div>
<div class="mb-3"><label class="form-label">Tambahkan tautan YouTube (opsional)</label><input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=XXXXXXXXXXX"><div class="form-text">Simpan kategori sekali untuk setiap video YouTube yang ingin ditambahkan.</div></div>
<button class="btn btn-primary" type="submit">Simpan Kategori & Media</button><?php if($editGallery['id']):?><a class="btn btn-secondary" href="gallery.php">Selesai Edit</a><?php endif;?></form>
<?php if($editGallery['id']): $st=$conn->prepare('SELECT * FROM gallery_media WHERE gallery_id=? ORDER BY id DESC');$st->bind_param('i',$editGallery['id']);$st->execute();$mediaRows=$st->get_result();?><hr><h5>Media dalam kategori ini</h5><div class="row g-3"><?php while($m=$mediaRows->fetch_assoc()):?><div class="col-md-3"><div class="border rounded p-2 h-100"><?php if($m['media_type']==='image'):?><img src="../uploads/gallery/<?=htmlspecialchars(basename($m['media_path']))?>" class="img-fluid mb-2" alt="Foto galeri"><?php elseif($m['media_type']==='video'):?><video src="../uploads/gallery/<?=htmlspecialchars(basename($m['media_path']))?>" controls class="w-100 mb-2"></video><?php else:?><div class="ratio ratio-16x9 mb-2"><iframe src="<?=htmlspecialchars($m['media_path'])?>" allowfullscreen></iframe></div><?php endif;?><a class="btn btn-sm btn-outline-danger" href="gallery.php?delete_media=<?=(int)$m['id']?>" onclick="return confirm('Hapus media ini?')">Hapus media</a></div></div><?php endwhile;?></div><?php endif;?></div></div>
<div class="row g-4"><?php while($row=$gallery->fetch_assoc()):?><div class="col-xl-3 col-lg-4 col-md-6"><div class="card shadow-sm h-100"><div class="card-body"><h5><?=htmlspecialchars($row['title'])?></h5><?php $st=$conn->prepare('SELECT media_type,media_path FROM gallery_media WHERE gallery_id=?');$st->bind_param('i',$row['id']);$st->execute();$mr=$st->get_result();$count=$mr->num_rows;if($count===0 && $row['image'])$count=1;?><p class="text-muted small"><?=$count?> media</p><a class="btn btn-sm btn-warning" href="gallery.php?edit=<?=(int)$row['id']?>">Kelola media</a> <a class="btn btn-sm btn-danger" href="gallery.php?delete=<?=(int)$row['id']?>" onclick="return confirm('Hapus kategori beserta semua medianya?')">Hapus kategori</a></div></div></div><?php endwhile;?></div></div><?php include __DIR__."/includes/footer.php"; ?></body></html>
