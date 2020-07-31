<?php

require(dirname(__FILE__) . '/src/MercadoPago/Config/AbstractConfig.php');
require(dirname(__FILE__) . '/src/MercadoPago/Config/ParserInterface.php');
require(dirname(__FILE__) . '/src/MercadoPago/Config/Json.php');
require(dirname(__FILE__) . '/src/MercadoPago/Config/Yaml.php');
require(dirname(__FILE__) . '/src/MercadoPago/Config.php');
require(dirname(__FILE__) . '/src/MercadoPago/Version.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Lexer/AbstractLexer.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/DocLexer.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/Annotation/Target.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/Reader.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/Annotation.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/AnnotationException.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/AnnotationReader.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/AnnotationRegistry.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/DocParser.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/PhpParser.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/SimpleAnnotationReader.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/TokenParser.php');
require(dirname(__FILE__) . '/src/MercadoPago/Doctrine/Common/Annotations/IndexedReader.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entity.php');
require(dirname(__FILE__) . '/src/MercadoPago/Manager.php');
require(dirname(__FILE__) . '/src/MercadoPago/MetaDataReader.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Shared/Documentation.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Shared/Item.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Shared/Payer.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Shared/Payment.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Shared/PaymentMethod.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/AuthorizedPayment.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Card.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/CardToken.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Chargeback.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Customer.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/InstoreOrder.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/MerchantOrder.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Plan.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Pos.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Preapproval.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Preference.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Refund.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Shipments.php');
require(dirname(__FILE__) . '/src/MercadoPago/Entities/Subscription.php');
require(dirname(__FILE__) . '/src/MercadoPago/Generic/ErrorCause.php');
require(dirname(__FILE__) . '/src/MercadoPago/Generic/RecuperableError.php');
require(dirname(__FILE__) . '/src/MercadoPago/Generic/SearchResultsArray.php');
require(dirname(__FILE__) . '/src/MercadoPago/Annotation/RestMethod.php');
require(dirname(__FILE__) . '/src/MercadoPago/Annotation/Attribute.php');
require(dirname(__FILE__) . '/src/MercadoPago/Annotation/RequestParam.php');
require(dirname(__FILE__) . '/src/MercadoPago/Annotation/DenyDynamicAttribute.php');
require(dirname(__FILE__) . '/src/MercadoPago/Http/HttpRequest.php');
require(dirname(__FILE__) . '/src/MercadoPago/Http/CurlRequest.php');
require(dirname(__FILE__) . '/src/MercadoPago/RestClient.php');
require(dirname(__FILE__) . '/src/MercadoPago/SDK.php');





