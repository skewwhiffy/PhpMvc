ViewTags come in different flavours.

<@ template = BLAH_TEMPLATE @>
This is a template tag. The path to the template is BLAH_TEMPLATE. The templater
will look inside the /Content/Views/ folder for the template.

<@ templatemodel = $model.getTemplateModel() @>
This is a template model tag, used to define how the template's model is defined,
usually from the model passed to the view.

<@=EXPRESSION @>
This is an expression tag. This is shorthand for <?php echo EXPRESSION ; ?>.

<@ container = BLAH @>
This is a container tag. This indicates where content goes in a template.

<@ content = BLAH @>
These are content tags, used for defining where content goes into containers
in the containing template. This pair of content tags belong inside the container
called BLAH.
<@ endcontent @>
