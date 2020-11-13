<?php

namespace Controller;

class DbController
{
    /* --- Connect Information --- */
    protected $host = ''; // Database Host
    protected $user = ''; // Database Username
    protected $password = ''; // Database Password
    protected $database = ''; // Database Name
    protected $charset = ''; // Database Charset

    protected $msg = array();

    /* --- Class Construct --- */
    public function __construct()
    {
        $this->conn = $this->connect();
    }

    /* --- Database Connect --- */
    public function connect()
    {
        $this->conn = new \mysqli($this->host, $this->user, $this->password, $this->database);
        if (mysqli_connect_errno()) {
            printf("Connection failed: %s\
            ", mysqli_connect_error());
            exit();
        }
        // Change character set to utf8
        mysqli_set_charset($this->conn, $this->charset);
        return true;
    }

    /* --- Database Query Select --- */
    public function querySelect($table, $customSelect = null, $id = null, $filter = false, $order = '')
    {
        $query = "";
        if (isset($table) && !empty($table)) {
            if ($customSelect != null && !empty($customSelect)) {
                $query .= "SELECT {$customSelect} FROM {$table} ";
                if ($id != null && !empty($id) && $filter == true) {
                    $query .= " WHERE $id";
                }
                if ($order != '') {
                    $query .= " ORDER BY {$order}";
                }
            } else {
                $query .= "SELECT * FROM {$table}";
                if ($id != null && !empty($id) && $filter == true) {
                    $query .= " WHERE $id";
                }
                if ($order != '') {
                    $query .= " ORDER BY {$order}";
                }
            }
        } else {
            $msg['SELECT_ERROR'] = "you Must add Table Name";
        }
        if (isset($msg) && !empty($msg)) {
            return $msg;
        } else {
            return $query;
        }
    }

    /* --- Database Query Insert --- */
    public function queryInsert($table, $Data, $insertData)
    {
        if (isset($table) && !empty($table)) {
            if (isset($Data) && !empty($Data)) {
                if (isset($insertData) && !empty($insertData)) {
                    $query = "INSERT INTO {$table}({$Data}) VALUES ({$insertData});";
                } else {
                    $msg['INSERT_ERROR'] = "you Must add Inserted Data";
                }
            } else {
                $msg['INSERT_ERROR'] = "you Must add Data";
            }
        } else {
            $msg['INSERT_ERROR'] = "you Must add Table Name";
        }
        if (isset($msg) && !empty($msg)) {
            return $msg;
        } else {
            return $query;
        }
    }

    /* --- Database Query Update --- */
    public function queryUpdate($table, $updatedData, $id)
    {
        if (isset($table) && !empty($table)) {
            if (isset($updatedData) && !empty($updatedData)) {
                if (isset($id) && !empty($id)) {
                    $query = "UPDATE {$table} SET {$updatedData} WHERE {$id};";
                } else {
                    $msg['UPDATE_ERROR'] = "Error with id";
                }
            } else {
                $msg['UPDATE_ERROR'] = "you Must add Updated Data";
            }
        } else {
            $msg['UPDATE_ERROR'] = "you Must add Table Name";
        }
        if (isset($msg) && !empty($msg)) {
            return $msg;
        } else {
            return $query;
        }
    }

    /* --- Database Query Delete --- */
    public function queryDelete($table, $id)
    {
        if (isset($table) && !empty($table)) {
            if (isset($id) && !empty($id)) {
                $query = "DELETE FROM {$table} WHERE {$id};";
            } else {
                $msg['DELETE_ERROR'] = "Error with id";
            }
        } else {
            $msg['DELETE_ERROR'] = "you Must add Table Name";
        }
        if (isset($msg) && !empty($msg)) {
            return $msg;
        } else {
            return $query;
        }
    }

    /* --- Database Query Custom --- */
    public function queryCustom($sql)
    {
        if (isset($sql) && !empty($sql)) {
            $query = "$sql";
        } else {
            $msg['QUERY_CUSTOM_ERROR'] = "Query is empty";
        }
        if (isset($msg) && !empty($msg)) {
            return $msg;
        } else {
            return $query;
        }
    }

    /* --- Database Check Data --- */
    public function insertQuery($query)
    {
        $stmt = $this->conn->query($query);
        if ($stmt > 0) {
            $status = '1';
        } else {
            $status = '0';
        }
        $result = array('query' => $stmt, 'status' => $status);
        return $result;
    }

    /* --- Database Check Data --- */
    public function getAll($query)
    {
        $stmt = $this->conn->query($query);
        if (isset($stmt->num_rows)) {
            if ($stmt->num_rows > 0) {
                $status = 1;
                $length = $stmt->num_rows;
            } else {
                $status = 1;
                $length = 0;
            }
        }
        $result = array('query' => $stmt, 'length' => $length, 'status' => $status);
        return $result;
    }
    /* --- Database Get Data --- */
    public function fetch($stmt, $status)
    {
        if ($status == '1') {
            $data = array('data' => $stmt->fetch_assoc(), 'status' => $status);
        } else {
            $data = array('data' => 'there is no data', 'status' => $status);
        }
        return $data;
    }
    /* --- Database get All Data --- */
    public function fetchAll($stmt, $status)
    {
        if ($status == '1') {
            $data = []; //initialise empty array
            while ($row = $stmt->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = array();
        }
        return $data;
    }
    /* --- Database Last Insert Id --- */
    public function lastInsertId()
    {
        $id = $this->conn->insert_id;
        return $id;
    }
}
