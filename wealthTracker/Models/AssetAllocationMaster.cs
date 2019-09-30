using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data;
using System.Data.Common;
using System.Data.SqlClient;
using System.Linq;
using System.Web;
using wealthTracker.DAL;

namespace wealthTracker.Models
{
    public class AssetAllocationMaster
    {
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int RiskProfileID { get; set; }
        [Key]
        public string RiskProfileType { get; set; }
        public decimal AusShares { get; set; }
        public decimal IntShares { get; set; }
        public decimal Property { get; set; }
        public decimal AusFixedInterest { get; set; }
        public decimal IntFixedInterest { get; set; }
        public decimal Cash { get; set; }
        public decimal AttractiveAssets { get; set; }
        public string DisplayField { get; set; }
        public int ApplySort { get; set; }

        WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();
        public int Update(string oldName, string newName)
        {
            if (db.Database.Connection.State == ConnectionState.Closed)
                db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_AssetAllocationMaster_AddUpdateDeleteTitle";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("oldName", oldName));
            cmd.Parameters.Add(new SqlParameter("newName", newName));

            int result = cmd.ExecuteNonQuery();

            return result;
        }
    }
}