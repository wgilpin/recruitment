<?php
include "../tempfunc.php";

session_start();
$Obj = new pullclass("market");
$buynsel = "hNYtNkHmZzcpIUWpIp3EY4a13XZQo795b62usvi2MRn235nfn6PP_RVB4sEwxihso5tc85xFKh7eYCYXuLMr5SHBRsUbVv_A1riu28GLkz-cV_5BrW9s5RUa5qOGVJmD15aUkuF3ZbfPAdLK-6-V4IbwzdXOBZyi2Xc7ZrAb5c4YcC1G4n9rmoeodNyXUI7BcAx7qOHsgwGq_gTeAllXL89yiwJQHT2NtVEqn2LTZff65ecFnLGdRi-sPDhmopWhJ8RnCFZCue7XBJfofemgDfNYYZPoAdi4xP9kNQnmGv34dzxIjwmRWjJ3IzNOK0atTJuebheqFJ-WWrG--_VmljrpJNU9t1sAoV0InjCYQqGDErOtrbhYdvgEWAY3UC7rXwil2ar-ynKaTyPM1QH5yg2";
echo "<pre>";
print_r($Obj->_Return($buynsel));