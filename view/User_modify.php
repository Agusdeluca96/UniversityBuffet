  <?php
class User_modify extends TwigView {
    
    public function show($user, $rol, $token) {

        $twig = $this->returnTwig();
        $template = $twig->loadTemplate("user_modify.html.twig");
        
        $template->display(array('usuario' => $user[0], 'rol' => $rol, 'token' => $token));
    }  
}
?>