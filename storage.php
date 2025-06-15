<?php
session_start();
include 'config.php';

$userid = $_SESSION['userid'];
$base_url = 'http://localhost/Drop Sync'; 

if (!isset($userid)) {
    header('Location: login.php');
    exit;
}
$message="";

if (isset($_FILES['file']) && is_array($_FILES['file']) && !empty($_FILES['file']['name'])) {
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $fileNameWithoutExtension = substr($file_name, 0, strlen($file_name) - strlen($file_extension) - 1);

    if ($file_extension == 'php' || $file_extension == 'html') {
        $new_file_name = $file_name. '.js';
        $display_file_name = substr($new_file_name, 0, -3); // Hide the last 3 characters (".js")
    } else {
        $new_file_name = $file_name;
        $display_file_name = $new_file_name;
    }

    $file_path = "storage/$userid/$new_file_name";
    $file_size = $file['size'];

    // Check if the file name already exists in the table
    $query = "SELECT * FROM storage WHERE userid = '$userid' AND file_name = '$new_file_name'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Display a message if the file name already exists
        $message = "$display_file_name already exists.";
    } else {
        if (move_uploaded_file($file["tmp_name"], $file_path)) {
            $query = "INSERT INTO storage (userid, file_name, file_path, file_size) VALUES ('$userid', '$new_file_name', '$file_path', '$file_size')";
            $conn->query($query);
            echo "$display_file_name uploaded successfully."; // Return success message
        } else {
            echo "There was an error uploading $display_file_name."; // Return error message
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Drop Sync</title>
    <link rel="icon" type="image/png" href="images/icon.png">
    <script src="https://kit.fontawesome.com/5ee1250f7c.js" crossorigin="anonymous"></script>
    <link href="css/style3.css" rel="stylesheet" />
</head>
<body>
<header>
    <img src ='images/dropsync.png' class='logo'>
    <h2></h2>
</header>
<div class='box'>
<h1>Storage</h1>
    <p id="message"><?php echo $message;?></p>
    <form id="uploadForm" action="storage.php" method="post" enctype="multipart/form-data">
        <input type="file" id="fileInput" name="files[]" required>
        <button type="button" id="fileButton">
            <i class="fa-solid fa-cloud-arrow-up" title="Upload"></i>
        </button>
    </form>
    <script>
  const fileButton = document.getElementById('fileButton');
  const fileInput = document.getElementById('fileInput');

  fileButton.addEventListener('click', () => {
    fileInput.click();
  });

  fileInput.addEventListener('change', (event) => {
    event.preventDefault(); 
    if (fileInput.files.length > 0) {
      const formData = new FormData();
      formData.append('file', fileInput.files[0]);
      fetch('storage.php', {
        method: 'POST',
        body: formData
      })
      .then(() => {
        location.reload();
        alert('File uploaded successfully'); 
      });
    }
  });
</script>
 
    <i class="fa-solid fa-folder-plus" onclick="createFolder()" title="Create Folder"></i>
<form id="create-folder-form" action="create_folder.php" method="post" class="hidden">
    <i class='fa-solid fa-folder'></i><input type="text" name="folder_name" placeholder="Enter Folder Name" required>
    <input type="hidden" name="action" value="create_folder"> <!-- Add this line -->
    <button type="submit" class='tick'><i class='fa-solid fa-check' title="Create Folder"></i></button>
</form>

<script>
function createFolder() {
  const folderNameInput = document.querySelector('input[name="folder_name"]');
  if (folderNameInput.value !== '') {
    document.getElementById('create-folder-form').submit();
  }
  document.getElementById('create-folder-form').classList.toggle('hidden');
}
</script>
<ul id="file-list">
<?php
$query = "SELECT * FROM storage WHERE userid = '$userid'";
$result = $conn->query($query);

$folder_path = "storage/$userid/";
$files = array_diff(scandir($folder_path), array('..', '.'));
$folder_names = array();

function listFolders($path, &$folder_names) {
    $files = array_diff(scandir($path), array('..', '.'));
    foreach ($files as $file) {
        $file_path = $path . '/' . $file;
        if (is_dir($file_path)) {
            $folder_names[] = $file;
            listFolders($file_path, $folder_names);
        }
    }
}

listFolders($folder_path, $folder_names);

$folder_names_string = implode("<br>", $folder_names);

foreach ($files as $file) {
    $file_path = $folder_path . $file;
    $file_info = pathinfo($file_path);
    $file_extension = $file_info['extension'];
    $fileNameWithoutExtension = substr($file, 0, strlen($file) - strlen($file_extension) - 1); 



    if (is_dir($file_path)) {
      echo "<li>
      <span onclick='showFiles(\"$file_path\")' title='View Folder' class='foldername'><i class='fa-solid fa-folder' ></i> $file</span>
          <i class='fa-solid fa-pencil' onclick='showRenameForm(\"".md5($file_path)."\")' title='Rename Folder'></i>
          <a href='delete_folder.php?folder_path=".urlencode($file_path)."'><i class='fa-solid fa-trash' title='Delete Folder'></i></a>
          <a href='#' onclick=\"shareFile('{$base_url}/{$file_path}')\"><i class='fa-solid fa-share' title='Share Folder'></i></a>
          <div id='rename-form-".md5($file_path)."' style='display: none;'>
              <form action='rename_folder.php' method='post'>
                  <input type='hidden' name='folder_path' value='$file_path'>
                  <input type='text' name='new_folder_name' value='$file' placeholder='Enter new folder name'>
                  <button type='submit' name='submit' class='tick'><i class='fa-solid fa-check'></i></button>
              </form>
          </div>
          <ul id='file-list-$file_path' style='display: none; list-style: none;'>";
  
          if ($handle = opendir($file_path)) {
              while (($file_in_folder = readdir($handle)) !== false) {
                $new_path = $file_path . '/' . $file_in_folder;
                $filename = basename($new_path);
                $current_folder = dirname($new_path);
                $last_directory = basename($current_folder);
                if (!is_dir($new_path)) {
                    $filename = basename($new_path);
                    $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
                    $fileNameWithoutExtension = substr($filename, 0, strlen($filename) - strlen($file_extension) - 1);
                    $unique_id = md5($new_path . $fileNameWithoutExtension);

                    if (substr($file_in_folder, -7) == '.php.js') {
                        $displaynewfilename = str_replace('.js', '', $file_in_folder); // added this line to remove .js
                    } elseif (substr($file_in_folder, -8) == '.html.js') {
                        $displaynewfilename = str_replace('.js', '', $file_in_folder); // added this line to remove .js
                    } else {
                        $displaynewfilename = $file_in_folder;
                    }

                    echo "<li><a href='#' onclick='openFile(\"{$file_path}/{$file_in_folder}\")'title='View File' class='filename'><i class='fa-solid fa-file' ></i>  $displaynewfilename</a>
                    <a href='{$new_path}' download><i class='fa-solid fa-download' title='Download File'></i></a>
                    <a href='#' onclick=\"shareFile('{$base_url}/{$new_path}')\"><i class='fa-solid fa-share' title='Share File'></i></a>
                    <i class='fa-solid fa-pencil' onclick='showRenameForm(\"{$unique_id}\")' title='Rename File'></i>
                    <a href='delete_file.php?file_path=" . urlencode($new_path) . "'><i class='fa-solid fa-trash' title='Delete File'></i></a>
                    <i class='fa-solid fa-up-down-left-right' onclick='showMoveFolderModal(\"move-folder-modal-{$unique_id}\")' title='Move File'></i>
                </li>
                <dialog id='move-folder-modal-{$unique_id}' class='modal'>
                    <div class='modal-content'>
                        <button class='closebtn' onclick='closeMoveFolderModal(\"move-folder-modal-{$unique_id}\")'><i class='fa-solid fa-x'></i></button>
                        <h3>Move to</h3>
                        <form action='move.php' method='post'>
                        <select id='folder-select' onchange='this.nextElementSibling.classList.toggle(\"open\"); document.getElementById(\"selected_folder\").value = this.value;'>
                        <option value='$last_directory' disabled selected hidden>$last_directory</option>
                        <option value=''>back to main</option>
                        " . implode("", array_map(function($folder_name) {
                        return "<option value='$folder_name'> $folder_name</option>";
                        }, explode("<br>", $folder_names_string))) . "
                        </select>
                            <input type='hidden' name='selected_folder' id='selected_folder'>
                            <input type='hidden' name='file_path' value='" . $new_path . "'>
                            <button type='submit' class='tick'><i class='fa-solid fa-check'></i></button>
                        </form>
                    </div>
                </dialog>
                <form id='rename-form-{$unique_id}' action='rename.php' method='post' style='display:none'>
                    <input type='hidden' name='action' value='rename'>
                    <input type='hidden' name='file_path' value='" . $new_path . "'>
                    <input type='text' name='new_file_name' value='" . $fileNameWithoutExtension . "'>
                    <button type='submit' class='tick'><i class='fa-solid fa-check'></i></button>
                </form>";
                      

                  }
              }
              closedir($handle);
          } else {
              echo "<li>No files found</li>";
          }
  
          echo "</ul>
      </li>";
  }
   else {
        if (substr($file, -7) == '.php.js') {
            $display_file_name = substr($file, 0, -3); 
        } elseif (substr($file, -8) == '.html.js') {
            $display_file_name = substr($file, 0,-8). '.html'; 
        } else {
            $display_file_name = $file;
        }
        $unique_id = md5($file_path . $fileNameWithoutExtension);
        echo "<li>
        <span><a href='#' onclick='openFile(\"{$file_path}\")' title='View File' class='filename'><i class='fa-solid fa-file'></i> {$display_file_name}</a></span>
        <a href='{$file_path}' download><i class='fa-solid fa-download' title='Download File'></i></a>
        <a href='#' onclick=\"shareFile('{$base_url}/{$file_path}')\"><i class='fa-solid fa-share' title='Share File'></i></a>
        <i class='fa-solid fa-pencil' onclick='showRenameForm(\"{$unique_id}\")' title='Rename File'></i>
        <a href='delete_file.php?file_path=" . urlencode($file_path) . "'><i class='fa-solid fa-trash' title='Delete File'></i></a>
        <i class='fa-solid fa-up-down-left-right' onclick='showMoveFolderModal(\"move-folder-modal-{$unique_id}\")' title='Move File'></i>
    </li>
    <dialog id='move-folder-modal-{$unique_id}' class='modal'>
        <div class='modal-content'>
            <button class='closebtn' onclick='closeMoveFolderModal(\"move-folder-modal-{$unique_id}\")'><i class='fa-solid fa-x'></i></button>
            <h3>Move to</h3>
            <form action='move.php' method='post'>
                <select id='folder-select' onchange='this.nextElementSibling.classList.toggle(\"open\"); document.getElementById(\"selected_folder\").value = this.value;'>
                    <option value='' disabled selected hidden>Select a folder</option>
                    " . implode("", array_map(function($folder_name) {
                        return "<option value='$folder_name'> $folder_name</option>";
                    }, explode("<br>", $folder_names_string))) . "
                </select>
                <input type='hidden' name='selected_folder' id='selected_folder'>
                <input type='hidden' name='file_path' value='" . $file_path . "'>
                <button type='submit' class='tick'><i class='fa-solid fa-check'></i></button>
            </form>
        </div>
    </dialog>
    <form id='rename-form-{$unique_id}' action='rename.php' method='post' style='display:none'>
        <input type='hidden' name='action' value='rename'>
        <input type='hidden' name='file_path' value='" . $file_path . "'>
        <input type='text' name='new_file_name' value='" . $fileNameWithoutExtension . "'>
        <button type='submit' class='tick'><i class='fa-solid fa-check'></i></button>
    </form>";
    }
}
?>
<script>
function showFiles(filePath) {
    var fileListElement = document.getElementById('file-list-' + filePath);
    if (fileListElement.style.display === 'none') {
        fileListElement.style.display = 'block';
    } else {
        fileListElement.style.display = 'none';
    }
}
</script>
<script>
function showMoveFolderModal(modalId) {
  var modal = document.getElementById(modalId);
  modal.style.display = 'block';
}

function closeMoveFolderModal(modalId) {
  var modal = document.getElementById(modalId);
  modal.style.display = 'none';
}
</script>
<script>
    function showRenameForm(hash) {
        document.getElementById('rename-form-' + hash).style.display = 'block';
    }
</script>
<script>
  function shareFile(url) {
    alert("Share this file: " + url);
    // You can also use a more advanced dialog box or modal window if needed
  }
</script>
</ul>



<script>
                 // JavaScript function to show the rename form
        function showRenameForm(fileId) {
            var formId = 'rename-form-' + fileId;
            document.getElementById(formId).style.display = 'block';
        }
</script>

    <dialog id="file-dialog" >
    <button class='close' onclick="closeFile()"><i class="fa-solid fa-x"></i></button>
        <div style="text-align: center;">
            <iframe id="file-preview"></iframe>
            <div id="file-preview-message"></div>
        </div>
    </dialog>

    <script>
  function openFile(filePath) {
    const filePreview = document.getElementById('file-preview');
    filePreview.src = filePath;
    const fileDialog = document.getElementById('file-dialog');
    fileDialog.showModal();

    const restrictedExtensions = ['.exe', '.zip', '.rar', '.7z', '.gz', '.tar', '.dll', '.sys', '.bin', '.msi', '.pkg', '.dmg', '.iso', '.img', '.vhd', '.ova', '.torrent'];
    const fileExtension = filePath.substring(filePath.lastIndexOf('.'));

    if (restrictedExtensions.includes(fileExtension)) {
      console.log(`File extension ${fileExtension} is restricted`);
      const iframeDoc = filePreview.contentDocument || filePreview.contentWindow.document;
      iframeDoc.write('<p style="color: red; font-size: 30px; font-weight: bold;text-align:center;margin-top:150px">Sorry,This File Cannot Be Viewed.Download Instead</p>');
      document.querySelector("#file-dialog").style.background = "black"; 
      iframeDoc.close(); // This is important to close the document
    } else {
      const closeButton = document.createElement('button');
      closeButton.classList.add('close');
      closeButton.innerHTML = '<i class="fa-solid fa-x"></i>';
      closeButton.onclick = closeFile;
      fileDialog.appendChild(closeButton);
    }
  }

  function closeFile() {
    const filePreview = document.getElementById('file-preview');
    filePreview.src = '';
    const fileDialog = document.getElementById('file-dialog');
    fileDialog.close();
    location.reload();
  }
</script>
</div>
<footer> 
    <p>&copy; 2024 DropSync All Rigths Reserved</p>
    <p>A Project From<a href='https://kvydyp.csb.app'> Logic Nexus Lab</a></p>
</footer>
</body>
</html>
<?php
$conn->close();
?>