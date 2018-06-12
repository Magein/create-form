### 创建表单

    form_config_format.json 是创建表单提交的数据格式
     
    属性说明：
     
    title 不是html元素，只是创建表单的时候习惯性的加上描述，说明这个项的意思
     
    type 表单项类型，如 text radio select file checkbox等，用于前段渲染的时候使用，这个并不限制可以使用那些，只需要约定成俗即可
     
    name 可以为空，系统生成e{xxx}n{xxx}这种格式的，其中{xxx}是随机的三位整数，这值是不能重复的
     
    class 处理项的类(非html样式class属性)，很上面type不一定一一对应（text类型可以使用DateConfig处理等），可以定义一些特殊的项，需要提前注册才能正确处理，并且需要继承FormConfig类
     
    value 表单项的默认值 
     
    required 是否必填  1 必填 0 非必填
     
    placeholder  提示信息
     
    description 表单项的描述，
     
    message 验证值不通过的提示信息
     
    disabled 是否不可编辑 true  不可编辑  false 可编辑 默认值 false
     
    length 值的长度范围 数组类型的字符串 默认 [0,0] 表示不验证 如果拥有options属性，则长度也不验证，
    
    
### 工厂类

    类名:FormFactory
     
    说明：除makeFormConfig方法外，类中的方法都是针对由makeFormConfig方法返回的数据，都是基于对象的形式处理，
     
         而makeFormConfig方法就是把表单配置项由字符串转化为对象
          
    方法:
     
        registerFormConfig  注册表单项处理类，注册的类需要继承FormConfig
        
        makeFormConfig  验证提交的表单项数据是否正确，并且转化为对象
        
        transFormConfigToArray 把表单配置项转化为数组（原始数组是对象）
        
        transFormConfigToJson 把表单数据转为json字符串（跟makeFormConfig互逆）
        
        checkFormData 检测表单提交的数据是否正确，并且通过setValue方法设置value属性的值
        
        getFormData 把表单项中的值按照键值对的方式返回，键是name属性的值，值是value属性的值
        
        getFormConfig 把表单项的标题按照键值对的方式返回，键是name属性的值，值是title属性的值 
        
### 错误类
    
    类名：FormError
     
    说明：为了快速的定位配置项的错误信息，错误分为错误代码，错误信息，错误配置项三个类型
     
         错误代码：更好的描述错误信息，以及根据错误代码设置自己的错误提示，又或者是需要对用户屏蔽的的错误信息，利用错误代码可以自定义提示信息，
          
         错误信息：直观的错误信息，配置项设置不正确的直接显示
          
         错误配置：直接定位到那一个配置项出现错误，一般用于开发人员快速定位出错的配置项,可以使 title name 或者第几个配置项
         
### 配置项

    类名：FormConfig
     
    说明：表单配置项基类，使用 FormFactory 注册的表单项处理类都应该继承此类，用于设定表单类各个属性的初始化，初始化验证，表单数据验证处理方法    
    
    