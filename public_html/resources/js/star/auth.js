Ext.ns("STAR","STAR.Controllers");STAR.Controllers=["STAR.controller.login.Form","STAR.controller.register.Form"];Ext.define("STAR.controller.login.Form",{extend:"Ext.app.Controller",refs:[{ref:"Form",selector:"login-Form"},{ref:"Button",selector:'login-Form button[action="login"]'}],init:function(){var a=this;a.control({'login-Form textfield[name="username"]':{render:function(a){a.focus(!1,400)},specialkey:function(b,c){c.getKey()==c.ENTER&&a.getForm().fireEvent("submit")}},'login-Form textfield[name="password"]':{specialkey:function(b,c){c.getKey()==c.ENTER&&a.getForm().fireEvent("submit")}},'login-Form button[action="login"]':{click:function(){a.getForm().fireEvent("submit")}},
"login-Form":{submit:a.submit}})},submit:function(){var a=this,b=a.getButton(),c=a.getForm();b.disable();c.getForm().isValid()?c.getForm().submit({success:function(){window.location="/"},failure:function(){b.enable();a.showFailureMessage()}}):b.enable()},showFailureMessage:function(){if(void 0===this.msgCt)this.msgCt=Ext.DomHelper.insertFirst(document.body,{id:"msg-div"},!0);var a=Ext.DomHelper.append(this.msgCt,'<div class="msg"><h3>Failure!</h3><p>'+STAR.locale.login.msg.failure_login+"</p></div>",
!0);a.hide();a.slideIn("t").ghost("t",{delay:2E3,remove:!0})}});Ext.define("STAR.view.login.Form",{extend:"Ext.form.Panel",alias:"widget.login-Form",id:"STAR.login.Form",labelWidth:100,border:!1,buttonAlign:"right",initComponent:function(){this.items=[];this.buildUsernameField();this.buildPasswordField();this.buildRememberField();this.buildAccountField();Ext.apply(this,{buttons:[{text:STAR.locale.common.button.login,action:"login"}]});this.callParent(arguments)},buildUsernameField:function(){this.items.push({xtype:"textfield",name:"username",fieldLabel:STAR.locale.login.field.username,
allowBlank:!1,width:400})},buildPasswordField:function(){this.items.push({xtype:"textfield",name:"password",fieldLabel:STAR.locale.login.field.password,inputType:"password",allowBlank:!1,width:400})},buildRememberField:function(){this.items.push({xtype:"checkbox",name:"remember",boxLabel:STAR.locale.login.field.remember,labelAligh:"right",checked:!0})},buildAccountField:function(){this.items.push({html:'<a href="/auth/register">'+STAR.locale.login.msg.about_account+"</a>",border:!1,layout:"fit"})}});Ext.define("STAR.view.login.Panel",{extend:"Ext.panel.Panel",requires:["STAR.view.login.Form"],initComponent:function(){var a=this.buildHtml();Ext.apply(this,{border:!1,autoScroll:!0,items:[{border:!1,html:a},{xtype:"login-Form",api:{submit:User.login}}]});this.callParent(arguments)},buildHtml:function(){return'<div class="auth-login-title"><img src="/resources/image/star.png" /><h1 class="wire-one auth-login-star">STAR</h1></div>'}});Ext.define("STAR.controller.register.Form",{extend:"Ext.app.Controller",refs:[{ref:"Form",selector:"register-Form"}],init:function(){this.control({"register-Form":{submit:this.submit}})},submit:function(a){var b=this.getForm();a.disable();b.getForm().isValid()?Ext.Msg.confirm("Confirm",STAR.locale.register.confirm.register,function(c){if("yes"==c){var d=new Ext.LoadMask(Ext.getBody(),{msg:STAR.locale.common.mask.wait});d.show();b.getForm().submit({success:function(){d.hide();Ext.Msg.show({title:"Success!",
msg:STAR.locale.register.msg.registed,icon:Ext.Msg.INFO,closable:!1,buttons:Ext.Msg.OK,fn:function(){Ext.getBody().fadeOut({callback:function(){window.location="/"}})}})},failure:function(b,c){d.hide();a.enable();Ext.Msg.show({title:"Caution!",msg:c.result.msg,icon:Ext.Msg.ERROR,buttons:Ext.Msg.OK})}})}else a.enable()}):a.enable()}});Ext.define("STAR.view.register.Form",{extend:"Ext.form.Panel",alias:"widget.register-Form",labelWidth:100,border:!1,buttonAlign:"right",initComponent:function(){var a=this;a.buildVtypes();a.items=[];a.buildUsernameField();a.buildPasswordField();a.buildRetypePasswordField();Ext.apply(a,{buttons:[{text:STAR.locale.common.button.submit,handler:function(b){a.fireEvent("submit",b)}}]});a.callParent(arguments)},buildVtypes:function(){var a=this;Ext.apply(Ext.form.field.VTypes,{register:function(a){return/^[!-\.:-@\[-`{-~a-zA-Z0-9]+$/.test(a)?
!0:!1},registerText:STAR.locale.register.msg.errorusername,retype:function(b){return a.down('textfield[name="password"]').getValue()===b?!0:!1},retypeText:STAR.locale.register.msg.error_retype})},buildUsernameField:function(){this.items.push({xtype:"textfield",name:"username",fieldLabel:STAR.locale.login.field.username,allowBlank:!1,minLength:4,vtype:"register",width:400,listeners:{render:function(a){a.focus(!1,400)}}})},buildPasswordField:function(){this.items.push({xtype:"textfield",name:"password",
fieldLabel:STAR.locale.login.field.password,inputType:"password",allowBlank:!1,minLength:6,vtype:"register",width:400})},buildRetypePasswordField:function(){this.items.push({xtype:"textfield",name:"retype-password",fieldLabel:STAR.locale.login.field.retype_password,inputType:"password",allowBlank:!1,vtype:"retype",width:400})}});Ext.ns("STAR");Ext.Loader.setConfig({enabled:!0,paths:{Ext:"/ext/src","Ext.ux":"/src/ux",STAR:"/src/auth"}});Ext.direct.Manager.addProvider(STAR.REMOTING_API);Ext.application({controllers:STAR.Controllers,launch:function(){Ext.get("auth-login-container")?Ext.create("STAR.view.login.Panel",{renderTo:"auth-login-container"}):Ext.get("auth-register-container")&&Ext.create("STAR.view.register.Form",{renderTo:"auth-register-container",api:{submit:User.register}})}});
