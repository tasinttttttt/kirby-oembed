module.exports = {
  "extends": [
    "eslint:recommended",
    "plugin:vue/recommended"
  ],
  "rules": {
    "quotes": [2, "single", {"avoidEscape": true}],
    "semi": [2, "never", {"beforeStatementContinuationChars": "any"}]
  },
  "env": {
    "browser": true
  }
}
