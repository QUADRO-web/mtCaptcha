# mtCaptcha
mtCaptcha MODX Extra


## Installation
Install the extra via MODX package manager

## Create Access Keys
Signup for a free account on https://www.mtcaptcha.com/ and create a private and site key. 

## System settings
Add private and site key to your system settings

## Setup Example
```
[[!FormIt?
   &hooks=`mtCaptcha`
   &validate=`name:required`
]]

<form action="[[~[[*id]]]]" method="post">
    <div>
        <label>Name: </label>
        <input type="text" name="name" value="[[!+fi.name]]">
        <span class="error">[[!+fi.error.name]]</span>
    </div>
    
    [[!mtCaptchaRenderer]]
    
    <button type="submit">send</button>
</form>
```

### Templating mtCaptcha
```
[[!mtCaptchaRenderer?
    &tpl=`yourCustomChunk`
]]
```

## Configuration and Styling
You can set the different settings inside your custom mtCaptcha chunk in the script section: https://www.mtcaptcha.com/dev-guide-quickstart
You also find a generator for this settings here: https://service.mtcaptcha.com/mtcv1/demo/index.html
