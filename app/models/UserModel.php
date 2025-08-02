<?php
/**
 * Class for User model
 */
require BASE_PATH . '/core/Model.php';

class UserModel extends Model
{
    function __construct($table = 'user')
    {
        $this->table = $table;
    }

    public function getAll()
    {
        $conn     = $this->connectDB();
        $result   = null;

        if ($conn) {
            $sql      = "SELECT * FROM ".$this->table." ORDER BY id ASC";
            $resource = $conn->query($sql);
            if ($resource) {
                $result = $resource->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        return $result;
    }

    public function getByID($id)
    {
        $conn   = $this->connectDB();
        $result = array();

        if ($conn)
        {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result ? $result[0] : array();
    }

    public function getByEmail($email)
    {
        $conn = $this->connectDB();
        $result = null;

        if ($conn) {
            $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $result;
    }

    public function getByUsername($username)
    {
        $conn = $this->connectDB();
        $result = null;

        if ($conn) {
            $sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['username' => $username]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $result;
    }

    public function insert($data = array())
    {
        $conn   = $this->connectDB();
        $result = false;

        if ($conn) {
            $sql = "INSERT INTO {$this->table} (username, first_name, last_name, email, user_type, password)
                    VALUES (?,?,?,?,?,?)";

            $result = $conn->prepare($sql)->execute([
              $data['username'], $data['first_name'], $data['last_name'], $data['email'], $data['user_type'], $data['password']
            ]);
        }
        return $result;
    }

    public function update($data)
    {
        $result = false;
        $conn   = $this->connectDB();

        if ($conn) {
            // If password is provided, update it; otherwise, leave it unchanged
            if (!empty($data['password'])) {
                $sql = "UPDATE {$this->table} 
                        SET username=?, first_name=?, last_name=?, email=?, user_type=?, password=?
                        WHERE id=?";
                $result = $conn->prepare($sql)->execute([
                    $data['username'], $data['first_name'], $data['last_name'], $data['email'], $data['user_type'], $data['password'], $data['id']
                ]);
            } else {
                $sql = "UPDATE {$this->table} 
                        SET username=?, first_name=?, last_name=?, email=?, user_type=?
                        WHERE id=?";
                $result = $conn->prepare($sql)->execute([
                    $data['username'], $data['first_name'], $data['last_name'], $data['email'], $data['user_type'], $data['id']
                ]);
            }
        }

        return $result;
    }

    public function delete($id)
    {
        $conn   = $this->connectDB();
        $result = false;

        if ($conn)
        {
            $sql      = "DELETE FROM {$this->table} WHERE id=?";
            $result   = $conn->prepare($sql)->execute([$id]);
        }

        return $result;
    }
}
