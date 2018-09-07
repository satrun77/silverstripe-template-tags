# Section block 
###### SilverStripe template parser

Provide a section syntax in template. Its similar implementation to <% include %> but with extra features:

- Load template by hard-coding the template name as the first parameter or provide a variable.
```
<% section TemplateName %><% end_section %>
```
Or,
``` 
<% section $TemplateName %><% end_section %>
```

- The body of the block can include HTML to be passed to the template in '$Content' variable

```
<% section TemplateName %>
    <h1>Hello world</h1>
<% end_section %>
```

TemplateName.ss
```
<div>
   {$Content} <--- will output <h1>Hello world</h1>
</div>
```

- The body of the block can include other syntax such as <% include %>
```
<% section TemplateName %>
    <h1>Hello world</h1>
    <% include Icon %>
<% end_section %>
```

- You can pass parameters, one per line! not in same line as '<% include %>
```
<% section TemplateName %>
    <% arg 'Theme=$Theme' %>
    <% arg 'Someone=Github user' %>
    <h1>Hello world</h1>
<% end_section %>
```

TemplateName.ss
```
<div class="theme--{$Theme}"> <---- will output the value of argument
   {$Content}  <------------------- will output <h1>Hello world</h1>
   <p>Are you a {$Someone}</p> <--- will output Github user
</div>
```
