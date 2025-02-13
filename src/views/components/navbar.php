<?php
class Navbar{
    public static function get_admin(){
        $user = User::GetUserFromSession();
        return (
            "
            <nav class='h-[10vh] w-full bg-gray-800 text-white flex justify-between items-center p-4 px-12'>
                <div><a href='/admin'>Admin Panel</a></div>
                <div class='flex gap-x-8'>
                    <div><a href=''>Hello, " .  $user->getUsername() ?? 'unauthenticated user' . " .</a></div>        
                    <div><a href='/auth/logout'>Log out</a></div>        
                </div>
            </nav>
            "

        );
        
    }
    
}
?>

