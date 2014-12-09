<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="error" colspan="3"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else { %}
        <td colspan=2 class="name" style="width: 140px !important; font-size: 8pt;">
            <div style="width: 120px !important; word-wrap: break-word;">
              <a href="{%=file.returl%}" title="{%=file.name%}">
              (Файл завантажено) </a>
            </div>
        </td>
        <td class="delete" style="width: 40px !important;">
            <button class="btn btn-danger btn-small" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                <i class="icon-trash icon-white" title="Видалити"></i>
                <span></span>
            </button>
        </td>
        {% } %}

    </tr>
{% } %}
</script>
