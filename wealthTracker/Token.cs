using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace wealthTracker
{
    public class Token
    {
        public string AccessToken { get; set; }

        public string TokenType { get; set; }

        public int ExpiresIn { get; set; }

        public string RefreshToken { get; set; }
    }
}