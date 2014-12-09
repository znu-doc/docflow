<!-- The template to display files available for upload -->

<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        {% if (file.error) { %}
            <td class="error"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td class="start" style="width: 40px !important;">{% if (!o.options.autoUpload) { %}
                <div style="height: 40px;">
                <button class="btn btn-primary"  title="Завантажити">
                    <i class="icon-upload icon-white"></i>
                </button>
                </div>
            {% } %}</td>
        {% } else { %}
            <td></td>
        {% } %}
        <td class="cancel" style="width: 40px !important;">{% if (!i) { %}
            <button class="btn btn-warning" title="Скасувати">
                <i class="icon-ban-circle icon-white" ></i>
            </button>
        {% } %}</td>
        <td class="name" style="width: 100px !important; font-size: 8pt;">
          <div style="width: 100px !important; word-wrap: break-word;">{%=file.name%}
          </div>
        </td>
    </tr>
{% } %}
</script>

