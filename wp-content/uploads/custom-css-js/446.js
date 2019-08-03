<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ ){
 var my_uploading_file= jQuery('#my_uploading_file');
  
 my_uploading_file.attr('onchange', 'PreviewImage();');
  jQuery('#uploadPreview').on('click',function(){
  my_uploading_file.click();
  });
   
});
  function PreviewImage() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("my_uploading_file").files[0]);

        oFReader.onload = function (oFREvent) {
          
            document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
    };</script>
<!-- end Simple Custom CSS and JS -->
