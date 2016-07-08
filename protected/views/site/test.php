<form action="submittest" method="post">
    <textarea name="editor1" id="editor1" rows="10" cols="80">
        This is my textarea to be replaced with CKEditor.
    </textarea>
    <input type="submit" value="TTTTT" />
    <script>
        CKEDITOR.replace( 'editor1' );
    </script>
</form>