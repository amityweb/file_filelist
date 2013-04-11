file_filelist
============

PyroCMS Fieldtype that adds a File Select List for a chosen folder to the Streams Fieldtypes.

The filelist field type outputs the following nested variables:

{{ field_slug:filename }}		File name of the image.
{{ field_slug:path }}			Full path to the image - compatible with older projects
{{ field_slug:image }}			Full path to the image - consistent with core image fieldtype
{{ field_slug:ext }}			The image extension.
{{ field_slug:description }}	The image description.
{{ field_slug:mimetype }}		The image mimetype.
{{ field_slug:width }}			Width of the full image.
{{ field_slug:height }}			Height of the full image.