<?php
require_once('connect.php');

if(!empty($_POST)){
    $minimum = intval($_POST['minimum']);
    $maximum = intval($_POST['maximum']);

    $sql = "UPDATE `rules` SET position=$minimum WHERE type='minimum';";
    $sql .= "UPDATE `rules` SET position=$maximum WHERE type='maximum';";
    $sql .= "DELETE FROM `rules` WHERE `type` <> 'maximum' and `type` <> 'minimum';";

    $rules = json_decode($_POST['rules'], true);
    foreach($rules as $rule){
        $type = strval($rule['type']);
        $position = intval($rule['position']);
        $symbol = strval($rule['symbol']);
        $sql .= "INSERT INTO `rules` (`type`, `position`, `symbol`) VALUES ('$type', '$position', '$symbol');";
    }

    if ($conn->multi_query($sql) !== TRUE){
        echo "Error";
    }
} else {
    // put all rules to $rules array, $minimum and $maximum variables
    include('rules.php');
}
    
    $conn->close();
?>

<script src="https://unpkg.com/vue"></script>

<script>
window.onload = function () {
    var v = new Vue({
        el: '#rules',
        data: {
            minimum: <?php echo $minimum; ?>,
            maximum: <?php echo $maximum; ?>,

            newRule: {
                key: 0,
                type: '',
                position: '',
                symbol: ''
            },
            rules: <?php echo json_encode($rules); ?>,
            types: {
                'number': 'character is always a number',
                'letter': 'character is always a letter',
                'symbol': 'character is always the specialized symbol'
            }
        },
        computed: {
            validation: function () {
                return {
                    position: ((this.newRule.position < this.maximum) && (0 < this.maximum)) && (this.newRule.position > 0),
                    type: this.newRule.type != '',
                    symbol: (((this.newRule.type == 'symbol') && (this.newRule.symbol != ''))
                             || ((this.newRule.type != 'symbol') && (this.newRule.symbol == '')))
                }
            },
            symbolVisible:  function () {
                return this.newRule.type == 'symbol';
            },
            isValid: function() {
                return ((this.validation.position && this.validation.type) && this.validation.symbol);
            }
        },
        methods: {
            changeMinimum: function () {
                if (this.minimum > this.maximum) this.maximum = this.minimum;
            },
            changeMaximum: function () {
                if (this.minimum > this.maximum) this.minimum = this.maximum;
            },

            addRule: function () {
                if (!this.isValid) return;
                this.rules.push({
                    key: this.newRule.key++,
                    type: this.newRule.type,
                    position: this.newRule.position,
                    symbol: this.newRule.symbol
                });
                this.newRule.position = '';
                this.newRule.symbol = '';
            },
            removeRule: function (key) {
                this.rules.splice(key, 1);
            }
        }
    });
}
</script>
</head>
<body>

<div class="container">
    <div vlass="row">
    <div class="col-md-8">

    <h1 class="mt-4">Settings Page</h1>

    <div id="rules">
            <div class="form-group">
        Minimum: <input name="minimum" class="form-control" type="number" size="40" v-model.number="minimum" v-on:change="changeMinimum">
        Maximum: <input name="maximum" class="form-control" type="number" size="40" v-model.number="maximum" v-on:change="changeMaximum">
            </div>
        <ul class="list-group" is="transition-group">
            <li class="list-group-item" v-for="(rule, key) in rules" :key="key">
                <span>{{types[rule.type]}} on position {{rule.position}}</span> <span class="badge badge-primary" v-if="rule.symbol"> "{{rule.symbol}}" </span>
                <button class="btn btn-light" v-on:click="removeRule(key)">X</button>
            </li>
        </ul>

        <form id="form" v-on:submit.prevent="addRule">
           <div class="form-row mt-4">
                <div class="col">
                    <select class="custom-select" v-model="newRule.type">
                        <option v-for="(value, key) in types" :value="key">{{value}}</option>
                    </select>
                </div>
                <div class="col">
                    <input type="number" class="form-control" v-model.number="newRule.position">
                </div>
                <div class="col">
                    <input v-show="symbolVisible" class="form-control" type="text" maxlength="1" sixe="1" v-model="newRule.symbol">
                </div>
                <div class="col">
                    <input type="submit" class="btn btn-light" value="Add Rule">
                </div>
            </div>
        </form>

        <ul class="list-group mt-4">
            <li class="list-group-item list-group-item-danger" v-show="!validation.type">Type cannot be empty.</li>
            <li class="list-group-item list-group-item-danger" v-show="!validation.position">Incorrect postion value.</li>
            <li class="list-group-item list-group-item-danger" v-show="!validation.symbol">Empty symbol value.</li>
        </ul>

        <form method="post">
            <input type="hidden" name="minimum" :value="minimum">
            <input type="hidden" name="maximum" :value="maximum">
            <input type="hidden" name="rules" :value="JSON.stringify(this.rules)">

            <div class="form-group mt-4">
                <input type="submit" class="btn btn-light" value="Save all">
            </div>
        </form>
    </div>

    </div>
    </div>
</div>

</body>