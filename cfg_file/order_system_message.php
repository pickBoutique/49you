<?php
/*
 * 系统内置通知
 *
 * Creater Jason 2010-12-13
 *
 */

$cfg_member_title = array(1=>"订单号：{$rs[order_no]}业务问题通知", 2=>" [订单号：{$order_no}]业务缴费通知", 3=>" [订单号：{$order_no}]内容变更通知", 4=>" [订单号：{$order_no}]支付成功通知", 5=>" [订单号：{$order_no}]业务退款通知", 6=>" [订单号：{$order_no}]业务成功通知", 7=>" [订单号：{$order_no}]提现成功通知", 8=>" [订单号：{$order_no}]业务失败通知", 9=>" [订单号：{$order_no}]业务预开通通知", 10=>" [发票号：{$invoice_no}]申请发票业务成功通知", 11=>" [订单号：{$order_no}]申请发票业务取消通知");  //发送反馈给用户用到的标题，标题数字号与内容数字号相对应
		  
$cfg_member_content = array(1=>"<p>尊敬的 {$contact_name} ，您好！</p>
								<p>　　您的订单 [订单号：{$order_no}] 中的 [产品名称：{$product_name}] 存在下列问题：</p>
								<p><font color='red'>这里的文字请勿修改</font></p>
								<p>　　请在会员中心的 “<a href='../member/problem-list.php'>有问题业务</a>” 查看详细情况，并且回复有关信息。</p>
								<p>　　如果您有疑问，请在会员中心提交 “<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p align='right'>广东互易科技有限公司</p>
								", 
							2=>"<p>尊敬的 {$contact_name} ，您好！</p>
								<p>　　您的订单 [订单号：{$order_no}] 中的 [产品名称：{$product_name}] 需要缴纳费用 [￥{$order_money}元] 。</p>
								<p>　　请在会员中心的 “<a href='../member/order-list.php'>订单列表</a>” 查看详细情况，并且进入 “<a href='../member/unpaid-list.php'>未付款业务</a>” 缴纳费用。</p>
								<p>　　如果您有疑问，请在会员中心提交 “<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p align='right'>广东互易科技有限公司</p>
								", 
							3=>"<p>尊敬的 {$contact_name} ，您好！</p>
								<p>　　您的订单 [订单号1] 因以下产品的问题需要进行拆分处理，以下产品将拆分到新的订单号： [订单号2] 。原订单号下的其它产品保持不变，业务将继续进行。</p>
								<p><table width='200px' align='center'><tr><td>产品编号</td><td>产品类型</td><td>产品名称</td><td>订购年限</td><td>金额小计</td></tr>
									<tr><td></td><td></td><td></td><td></td><td></td></tr>
									<tr><td colspan='5' align='right'>金额总计：</td></tr></table></p>
								<p>　　您可在会员中心的 “<a href='../member/order-list.php'>订单列表</a>” 查看详细情况。</p>
								<p>　　如果您有疑问，请在会员中心提交 “<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p align='right'>广东互易科技有限公司</p>
								", 
							4=>"<p>尊敬的 {$contact_name} ，您好！</p>
								<p>　　您的订单 [订单号：{$order_no}] 已经支付成功，业务正在进行中，业务成功后您将收到《业务成功通知》。</p>
								<p>　　如果您有疑问，请在会员中心提交 “<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p align='right'>广东互易科技有限公司</p>
								", 
							5=>"<p>尊敬的 {$contact_name} ，您好！</p>
								<p>　　您的订单 [订单号：{$order_no}] 已经退款成功。订单费用 [￥{$order_money}元] 已返回到您的会员中心钱包。</p>
								<p>　　如果您有疑问，请在会员中心提交 “<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p align='right'>广东互易科技有限公司</p>
								", 
							6=>"<p>尊敬的 {$contact_name} ，您好！<p>
								<p>　　您的订单 [订单号：{$order_no}] 业务已经成功。订单产品列表如下：</p>
								<p><table width='200px' align='center'><tr><td>产品编号</td><td>产品类型</td><td>产品名称</td><td>订购年限</td><td>金额小计</td></tr>
									<tr><td></td><td></td><td></td><td></td><td></td></tr>
									<tr><td colspan='5' align='right'>金额总计：</td></tr></table></p>
								<p>　　如果您有疑问，请在会员中心提交 “<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p>　　顺祝</p>
								<p>商祺！</p>
								<p align='right'>广东互易科技有限公司</p>
								", 
							7=>"<p>尊敬的 {$contact_name} ，您好！</p>
								<p>　　您的订单 [订单号] 业务已经成功。提现金额 [￥{$order_money}元] 已转帐到您的指定帐户。</p>
								<p><table width='200px' align='center'><tr><td colspan='2'>您的账户信息</td></tr>
									<tr><td>开户银行：{$financial_open_bank}</td><td></td></tr>
									<tr><td>开户名称：{$financial_open_name}</td><td></td></tr>
									<tr><td>帐号：{$financial_open_number}</td><td></td></tr></table></p>
								<p>　　如果您有疑问，请在会员中心提交 “<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p align='right'>广东互易科技有限公司</p>
								", 
							8=>"<p>尊敬的 {$contact_name} ，您好！</p>
								<p>　　您的订单 [订单号：{$order_no}] 业务已经失败。</p>
								<p>　　您可在会员中心的 “<a href='../member/order-list.php'>订单列表</a>” 查看详细情况。</p>
								<p>　　如果您有疑问，请在会员中心提交 “<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p align='right'>广东互易科技有限公司</p>
								", 
							9=>"<p>尊敬的 {$contact_name} ，您好！</p>
								<p>　　您的订单 [订单号：{$order_no}] 中的 [产品名称：{$product_name}] 已经注册，现在等待中国互联网络信息中心（CNNIC）的审核，一般在3-7个工作日后会有审核结果。</p>
								<p>　　审核通过后，您的域名（或网址）将正式注册成功。</p>
								<p>　　如果审核不通过，CNNIC将注销该域名（或网址），注册费用将返回到您的会员中心钱包。</p>
								<p>　　如果您有疑问，请在会员中心提交 “<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p align='right'>广东互易科技有限公司</p>
								", 
							10=>"<p>尊敬的 {$contact_name} ，您好！</p>
								<p>　　您的发票 [发票号：{$invoice_no}] 申请业务已经成功。</p>
								<p>　　请您注意查收发票。</p>
								<p>　　如果您有疑问，请在会员中心提交“<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p align='right'>广东互易科技有限公司</p>
								", 
							11=>"<p>尊敬的 {$contact_name} ，您好！</p>
								<p>　　您的订单 [订单号：{$order_no}] 申请发票业务已经失败。</p>
								<p>　　如果您有疑问，请在会员中心提交 “<a href='../member/ask-add.php'>在线提问</a>” ，或者拨打服务热线：400-628-1118。</p>
								<p align='right'>广东互易科技有限公司</p>
								");  //发送反馈给用户用到的内容

?>																	