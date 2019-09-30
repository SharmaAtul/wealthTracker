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
    public class ProjectedData
    {
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int ProjectedDataID { get; set; }
        [Key]
        [Column(Order = 0)]
        public int ClientID { get; set; }
        [Key]
        [Column(Order = 1)]
        public int Year { get; set; }
        public decimal Value { get; set; }

        WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();
        public bool ImportProjectedData(int clientID, string csvYears, string csvValues, DateTime projectionDate, int fundingId)
        {
            db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_ImportProjectedData";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("clientId", clientID));
            cmd.Parameters.Add(new SqlParameter("years", csvYears));
            cmd.Parameters.Add(new SqlParameter("values", csvValues));
            cmd.Parameters.Add(new SqlParameter("lastProjectionDate", projectionDate));
            cmd.Parameters.Add(new SqlParameter("fundingId", fundingId));
            
            int result = cmd.ExecuteNonQuery();
            return result > 0;
        }

        public static decimal GetByUserIdAndYear(int clientID, int year)
        {
            WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();

            db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_ProjectedData_GetByUserIdAndYear";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("userId", clientID));
            cmd.Parameters.Add(new SqlParameter("year", year));

            object result = cmd.ExecuteScalar();

            if (result == null)
                return 0;
            else
                return decimal.Parse(result.ToString());
        }

        

        public List<ProjectedData> GetProjectedDataByClientId(int clientID)
        {
            db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_ProjectedData_Get";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("clientId", clientID));

            List<ProjectedData> projectedData;
            using (var reader = cmd.ExecuteReader())
            {
                if (reader.HasRows)
                    projectedData = reader.MapToList<ProjectedData>();
                else
                    projectedData = new List<ProjectedData>();
            }

            return projectedData;
        }
    }
}