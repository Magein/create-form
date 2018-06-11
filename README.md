### 创建表单

    form_config_format.json 是创建表单提交的数据格式
     
    属性说明：
    
    title 不是html元素，只是创建表单的时候习惯性的加上描述，说明这个项的意思
    
    type 表单项类型，如 text radio select file checkbox等，用于前段渲染的时候使用
     
    name 可以为空，系统生成e{xxx}n{xxx}这种格式的，其中{xxx}是随机的三位整数，这值是不能重复的
     
    class 处理项的类，很上面type不一定一一对应，可以定义一些特殊的项，需要提前注册才能正确处理，并且需要继承FormConfig类
     
    value 表单项的默认值 
     
    required 是否必填  1 必填 0 非必填
     
    placeholder  提示信息
     
    description 表单项的描述
     
    message 值非法的时候提示信息
     
    disabled 是否不可编辑 true  不可编辑  false 可编辑 默认值 false
     
    length 值的长度范围 数组类型的字符串 默认 [0,255]
    
    
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
    
    