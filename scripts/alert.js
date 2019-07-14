function remove(){
    if(!confirm('Are you sure you want to remove your friend?'))
        location.reload();
        else
            window.location.href = 'remove.php';
}