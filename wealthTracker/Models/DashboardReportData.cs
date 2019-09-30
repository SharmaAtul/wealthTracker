using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace wealthTracker.Models
{
    public class DashboardReportData
    {
        public string ApplyOrder { get; set; }
        public string key { get; set; }
        public string color { get; set; }
        public List<List<object>> values { get; set; }
    }
}