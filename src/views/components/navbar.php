<?php
class Navbar{
    static function get_admin(): string{
        return "<nav>
        <a href='/admin'>Dashboard</a>
        <a href='/auth/logout'>Logout</a>
        </nav>";
    }
    static function get_user(): string{
        return "<nav>
        <a href='/user/dashboard'>Dashboard</a>
        <a href='/user/logout'>Logout</a>
        </nav>";
    }
    static function get_unauthenticated(): string{
        return "<nav>
        <a href='/login'>Login</a>
        <a href='/register'>Register</a>
        </nav>";
    }
}
?>
