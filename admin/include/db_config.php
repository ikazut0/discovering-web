<?php

$host_name = 'localhost';
$user_name = 'root';
$user_pass = '';
$data_info = 'tourdb';

$connection_info = mysqli_connect($host_name, $user_name, $user_pass, $data_info);

mysqli_set_charset($connection_info, "utf8");

if (!$connection_info) {
    die("НЕ ВДАЛОСЯ ОТРИМАТИ ІНФОРМАЦІЮ З БАЗИ ДАНИХ. БУДЬ ЛАСКА, ЗВ'ЯЖІТЬСЯ С АДМІНІСТРАЦІЄЮ САЙТУ DISCOVERING.UA" . mysqli_connect_error());
}

if (!function_exists('filtrationData')) {
    function filtrationData($data)
    {
        foreach ($data as $key => $value) {
            $value = trim($value);
            $value = stripslashes($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            $data[$key] = $value;
        }
        return $data;
    }
}

if (!function_exists('selectData')) {
    function selectData($sql, $values, $data_types)
    {
        $connection_info = $GLOBALS['connection_info'];
        if ($stmt = mysqli_prepare($connection_info, $sql)) {
            mysqli_stmt_bind_param($stmt, $data_types, ...$values);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                mysqli_stmt_close($stmt);
                return $result;
            } else {
                mysqli_stmt_close($stmt);
                die("НЕМОЖЛИВО ВИКОНАТИ, АБО НАЛАДИТИ ЗАПИТ ТИПУ : SELECT-DATA");
            }
        } else {
            die("НЕМОЖЛИВО ВИКОНАТИ, АБО НАЛАДИТИ ЗАПИТ ТИПУ : SELECT-DATA");
        }
    }
}

if (!function_exists('selectAll')) {
    function selectAll($table)
    {
        $connection_info = $GLOBALS['connection_info'];
        $res = mysqli_query($connection_info, "SELECT * FROM $table");
        return $res;
    }
}

if (!function_exists('updateData')) {
    function updateData($sql, $values, $data_types)
    {
        $connection_info = $GLOBALS['connection_info'];
        if ($stmt = mysqli_prepare($connection_info, $sql)) {
            mysqli_stmt_bind_param($stmt, $data_types, ...$values);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $result;
            } else {
                mysqli_stmt_close($stmt);
                die("НЕМОЖЛИВО ВИКОНАТИ, АБО НАЛАДИТИ ЗАПИТ ТИПУ : UPDATE-DATA");
            }
        } else {
            die("НЕМОЖЛИВО ВИКОНАТИ, АБО НАЛАДИТИ ЗАПИТ ТИПУ : UPDATE-DATA");
        }
    }
}

if (!function_exists('insert')) {
    function insert($sql, $values, $data_types)
    {
        $connection_info = $GLOBALS['connection_info'];
        if ($stmt = mysqli_prepare($connection_info, $sql)) {
            mysqli_stmt_bind_param($stmt, $data_types, ...$values);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $result;
            } else {
                mysqli_stmt_close($stmt);
                die("НЕМОЖЛИВО ВИКОНАТИ, АБО НАЛАДИТИ ЗАПИТ ТИПУ : INSERT-DATA");
            }
        } else {
            die("НЕМОЖЛИВО ВИКОНАТИ, АБО НАЛАДИТИ ЗАПИТ ТИПУ : INSERT-DATA");
        }
    }
}

if (!function_exists('delete')) {
    function delete($sql, $values, $data_types)
    {
        $connection_info = $GLOBALS['connection_info'];
        if ($stmt = mysqli_prepare($connection_info, $sql)) {
            mysqli_stmt_bind_param($stmt, $data_types, ...$values);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $result;
            } else {
                mysqli_stmt_close($stmt);
                die("НЕМОЖЛИВО ВИКОНАТИ, АБО НАЛАДИТИ ЗАПИТ ТИПУ : DELETE-DATA");
            }
        } else {
            die("НЕМОЖЛИВО ВИКОНАТИ, АБО НАЛАДИТИ ЗАПИТ ТИПУ : DELETE-DATA");
        }
    }
}

?>