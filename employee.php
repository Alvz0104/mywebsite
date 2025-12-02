<?php

class EmployeeProfile
{
    private string $file = "data.json";
    private array $profiles = [];

    public function __construct()
    {
        if (file_exists($this->file)) {
            $raw = file_get_contents($this->file);
            $data = json_decode($raw, true);
            if (is_array($data)) {
                $this->profiles = $data;
            }
        }
    }

    public function getProfile(string $username): ?array
    {
        return $this->profiles[$username] ?? null;
    }

    public function saveProfile(string $username, string $name, string $position, string $department): array
    {
        $profile = [
            "id" => $this->profiles[$username]["id"] ?? uniqid("emp_", true),
            "username" => $username,
            "name" => $name,
            "position" => $position,
            "department" => $department,
            "updated_at" => date("c"),
        ];

        $this->profiles[$username] = $profile;
        $this->persist();

        return $profile;
    }

    public function deleteProfile(string $username): bool
    {
        if (!isset($this->profiles[$username])) {
            return false;
        }

        unset($this->profiles[$username]);
        $this->persist();

        return true;
    }

    private function persist(): void
    {
        file_put_contents($this->file, json_encode($this->profiles, JSON_PRETTY_PRINT));
    }
}

?>

