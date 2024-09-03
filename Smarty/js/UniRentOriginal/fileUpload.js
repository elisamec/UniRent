$(document).ready(function() {

      
    var readURL = function(input) {
       if (input.files && input.files[0]) {
             var reader = new FileReader();
             console.log(file);
             if (file==='editProfileStudent' || file==='editProfileOwner') {
               reader.onload = function (e) {
               $('.small').attr('src', e.target.result);
               }
               } else {
                  reader.onload = function (e) {
                $('.imageIcon').attr('src', e.target.result);
                  }
               }
    
             reader.readAsDataURL(input.files[0]);
       }
    }
   

    

    $(".file-upload").on('change', function(){
       readURL(this);
    });
    
    $(".label-button").on('click', function() {
       $(".file-upload").click();
    });
 });
 
if (document.getElementById('profileForm')) {
    document.getElementById('profileForm').addEventListener('submit', function(event) {
        var fileInput = document.getElementById('img');
        if (!fileInput.files.length) {
            fileInput.value = null;
        }
    });
}