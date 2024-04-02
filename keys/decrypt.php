<?php


require_once "../src/components/encryptionService/contract/IEncryptionService.php";
require_once "../src/components/encryptionService/impl/EncryptionService.php";

$encryptionService = new EncryptionService();


$encrypted =
    "6m8nUhyB103UEGyuzjs+6oUKNec3LROy0Bw6JXRpOJgq1yc1IWG+e5c9WgMgtdO1tCJYBPPCzYEpE70nCfc+Wcp4MnMzYLNAWgrxs6Ax5kiVUwVwcP1g0xHQ6FuLv15Wki4o3mkzmCpPb4tXKVGaxZQ3jOOFcV8OBpLujYGnmC+59iIzEk51B71o7FDeunWa4nVgBTJ6m5LTWurTBVl3WC3AJrpiAIazdbcQO9p\/J3QvYLnB2ZizPmN6vRKEsxAnEbe881UBGXitH3Mab6VXhirfgJ7pilwv7upoSoQwo1ZGhoWQqMwYoFnuBh+ncGrO7NIy+sliL1toEM0h0qAl4w==";





$privateKey = file_get_contents("./private_key.pem");
echo $encryptionService->decryptWithPrivateKey($encrypted, $privateKey);