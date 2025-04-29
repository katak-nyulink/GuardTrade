<?php

namespace App\Enums;

enum UserAdmin: int
{
    case Users = 1;
    case Roles = 2;
    case Permissions = 3;
    case Settings = 4;
    public function label(): string
    {
        return match ($this) {
            self::Users => 'Pengguna',
            self::Roles => 'Peran',
            self::Permissions => 'Izin',
            self::Settings => 'Pengaturan',
        };
    }
}
