<?php

namespace Controllers;

class SecretaryTvController extends UserController{

    public function displayContent()
    {
        return "<script>location.href = '". home_url('/secretary/homepage/') . "'</script>";
    }
}
