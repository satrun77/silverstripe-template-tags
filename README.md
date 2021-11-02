# Template Tags 
###### SilverStripe template parser

[![Build Status](https://scrutinizer-ci.com/g/satrun77/silverstripe-template-tags/badges/build.png?b=master)](https://scrutinizer-ci.com/g/satrun77/silverstripe-template-tags/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/satrun77/silverstripe-template-tags/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/satrun77/silverstripe-template-tags/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/satrun77/silverstripe-template-tags/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/satrun77/silverstripe-template-tags/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/moo/template-tags/v/stable?format=flat)](https://packagist.org/packages/moo/template-tags)
[![License](https://poser.pugx.org/moo/template-tags/license?format=flat)](https://packagist.org/packages/moo/template-tags)

Provide a several template tags for better template implementation. For example an improved `<% include %>`.

## Requirements

* SilverStripe CMS ^4.1

## Installation via Composer
	composer require moo/template-tags

## Usage

### Spaceless

Remove extra whitespaces from around the template syntax & HTML output.

``` 
<% spaceless %>
    ... Template syntax and HTML ...
<% end_spaceless %>

```

### Template

An alternative to `<% include %>`

``` 
<% template TemplateName %>
    <% set Theme %>$ClassName<% end_set %>
    <% set Someone %>
        Github user: {$ClassName}-{$ID}-{$MenuTitle.XML}
    <% end_set %>

    <h1>Hello world</h1>

<% end_template %>

```

#### Ways to define template name

- By string
```
<% template TemplateName %><% end_template %>

<% template 'Namespace\TemplateName' %><% end_template %>
```

- By variable or method within an object
``` 
<% template $TemplateName %><% end_template %>

<% template $Object.Method %><% end_template %>
```

#### The body of the template tag

- The body of the tag can include HTML, template logic, & defined arguments.

```
<% template TemplateName %>
    <h1>Hello world</h1>
<% end_template %>
```

- The body of the block can include other syntax such as <% include %>
```
<% template TemplateName %>
    <h1>Hello world</h1>
    <% include Icon %>
<% end_template %>
```

- You can pass parameters, one per line! not all in same line as '<% include %>
```
<% template TemplateName %>
    <% set Theme %>$Theme<% end_set %>
    <% set Someone %>
        Github user: {$Member.Name}
    <% end_set %>

    <h1>Hello world</h1>
<% end_template %>
```

TemplateName.ss 
`<% content %>` Placeholder for the content of the template ie. `<h1>Hello world</h1>`
```
<div class="theme--{$Theme}">
   <% content %> 
   <p>Are you a {$Someone}</p> 
</div>
```

## License

This module is under the MIT license. View the [LICENSE](LICENSE.md) file for the full copyright and license information.
