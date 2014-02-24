<form method='post'>
<table border='1'>
<tr><td>FBAPP_ID</td><td><input size='60' type='text' name='FBAPP_ID' value='<?=$admin['FBAPP_ID']?>' /></td></tr>
<tr><td>FBAPP_SECRET</td><td><input size='60' type='text' name='FBAPP_SECRET' value='<?=$admin['FBAPP_SECRET']?>' /></td></tr>
<tr><td>FBAPP_TITLE</td><td><input size='60' type='text' name='FBAPP_TITLE' value='<?=$admin['FBAPP_TITLE']?>' /></td></tr>
<tr><td>瀏覽器標題</td><td><input size='60' type='text' name='FBAPP_TITLE_TC' value='<?=$admin['FBAPP_TITLE_TC']?>' /></td></tr>
<tr><td>加頁籤網址</td><td><a target='_blank' href='<?=$tab?>'><?=$tab?></a></td></tr>
<tr><td colspan='2' style='color:blue;'><?= isset($msg)?$msg:'' ?></td></tr>
<tr><td colspan='2'><input type='submit' value='確認送出' /></td></tr>
</table>
</form>