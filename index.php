<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>This is my GitHub site</title>
	<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="./api.js"></script>
</head>
<body>
	<button id="connectNearWallet">Connect Near Wallet</button>
	<button id="connectWallet">Connect Metamask</button>
	<button id="callContract">Call Contract</button>
	<p id="address"></p>
	<p id="balance"></p>
	<p id="signature"></p>
	<p id="contractResult"></p>
</body>
<script>
	if (!window.ethereum) {
        alert('MetaMask not detected. Please try again from a MetaMask enabled browser.');
    }
	var web3 = new Web3(window.ethereum);
    var message = "I have read and accept the terms.\nPlease sign me in!";
	var address, signature, balance, contract;

	$(document).ready(async function () {
		cont = await loadContract(web3.eth);
		cont.setProvider(web3.currentProvider);
		contract = cont;
	});

	$('#connectWallet').click(async function () {
		address = (await web3.eth.requestAccounts())[0];
		signature = await web3.eth.personal.sign(message, address);
		web3.eth.getBalance(address, function (err, wei) {
			if (!err) {
				balance = web3.utils.fromWei(wei, 'ether');
				document.getElementById('balance').innerHTML = balance + " ETH";
			}
		});

		document.getElementById('address').innerHTML = address;
		document.getElementById('signature').innerHTML = signature;
		
	});

	$('#callContract').click(async function () {
		var result = await contract.methods
			.owner()
			.call()
			.then((res) => res)
			.catch((error) => {
				console.log(error.response)
			});
		$('#contractResult').text(result);
	});
</script>
</html>